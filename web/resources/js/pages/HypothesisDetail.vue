<template>
  <v-container
    class="d-flex flex-column py-6 my-md-2 px-md-16"
    :style="$vuetify.breakpoint.mdAndUp ? 'max-width: 900px' : ''"
    fluid
  >
     <Header :headerTitle="hypothesis.name"/>
      <template>
        <div
          class="d-flex flex-column"
          style="position: fixed"
          :style="
            $vuetify.breakpoint.mdAndUp
              ? 'width: 772px'
              : 'width: calc(100vw - 24px)'
          "
          absolute
        >
          <div class="py-2 d-flex justify-start flex-column">
            <v-subheader
              class="pa-md-0 d-flex"
              :class="
                $vuetify.breakpoint.mdAndUp
                  ? 'hypothesisSubTitle'
                  : 'spHypothesisSubTitle'
              "
            >
              <p class="ma-0 font-weight-bold" color="grey darken-1">ゴール</p>
            </v-subheader>
            <v-textarea
              label="ゴールを入力"
              v-model="hypothesis.name"
              @keyup.enter="edit"
              class="pa-0 text-h5"
              rows="1"
              auto-grow
              single-line
              solo
              flat
              hide-details
            ></v-textarea>
          </div>
          <div
            class="py-2 d-flex justify-start"
            :style="
              $vuetify.breakpoint.mdAndUp ? 'height: 80px' : 'height: 64px'
            "
          >
            <v-subheader
              class="d-flex align-self-center pa-md-0"
              :class="
                $vuetify.breakpoint.mdAndUp
                  ? 'hypothesisSubTitle'
                  : 'spHypothesisSubTitle'
              "
            >
              <p class="ma-0 font-weight-bold" color="grey darken-1">結果</p>
            </v-subheader>
            <v-col class="px-4 px-md-6 d-flex align-self-center">
              <v-btn
                class="mx-1"
                :result="result"
                @click="clickSuccess"
                :color="result === true ? 'green' : ''"
                size="36"
                icon
                text
              >
                <v-icon size="32">mdi-check-circle-outline</v-icon>
              </v-btn>
              <v-btn
                class="mx-1"
                :result="result"
                @click="clickFailure"
                :color="result === false ? 'pink' : ''"
                size="36"
                icon
                text
              >
                <v-icon size="32">mdi-close-circle-outline</v-icon>
              </v-btn>
            </v-col>
          </div>
          <div class="py-2">
            <div class="d-flex justify-space-between">
              <v-subheader
                class="pa-md-0"
                :class="
                  $vuetify.breakpoint.mdAndUp
                    ? 'hypothesisSubTitle'
                    : 'spHypothesisSubTitle'
                "
              >
                <p class="ma-0 font-weight-bold" color="grey darken-1">仮説</p>
              </v-subheader>
              <v-icon
                class="hidden-sm-and-down my-3"
                size="24"
                height="24"
                @click="onClickCreate"
                >mdi-plus-circle</v-icon
              >
            </div>
            <div
              class="overflow-y-auto d-flex flex-column"
              :class="$vuetify.breakpoint.mdAndUp ? 'cardStyle' : 'spCardStyle'"
            >
              <HypothesisCards :parent="hypothesis" :hypotheses="hypothesisList" :view="page" />
              <!-- PC版追加カード -->
              <NewAdditionalCard @clickAditional="onClickCreate" :category="category"/>
            </div>
          </div>
        </div>
        <!-- スマホ版追加ボタン -->
        <SpBottomBtn @clickAditional="onClickCreate" :headerTitle="page" />
      </template>
      <form class="form" @submit.prevent="submitForm()">
        <InputForm
          @onClickCancel="onClickCancel"
          @submitForm="submitForm"
          :category="category"
          :inputForm="inputForm"
        />
      </form>
  </v-container>
</template>

<script>
import Header from "../components/Header.vue";
import HypothesisCards from "../components/Cards/HypothesisCard.vue";
import NewAdditionalCard from "../components/Cards/NewAddtionalCard.vue";
import SpBottomBtn from "../components/Buttons/SpBottomBtn.vue";
import InputForm from "../components/InputForm.vue";
import { mapGetters, mapState } from 'vuex';

export default {
  components: {
    Header,
    HypothesisCards,
    NewAdditionalCard,
    SpBottomBtn,
    InputForm,
  },
  data: () => ({
    category: "仮説",
    page: "仮説詳細",
    result: null,
  }),
  computed : {
    ...mapState({
      hypothesis: (state) => state.hypothesis.hypothesis,
      apiStatus: (state) => state.auth.apiStatus,
    }),
   ...mapGetters({
      inputFormName: 'form/name',
      inputForm: 'form/inputForm',
      hypothesisList: 'hypothesis/hypothesisList',
    }),
    hypothesis: {
      get () {
        return this.$store.getters['hypothesis/hypothesis']
      },
      set (value) {
        this.$store.dispatch("hypothesis/setInputName", value);
      }
    },
  },
  methods: {
    clickSuccess () {
      switch (this.result) {
        case null:
          return (this.result = true);
        case true:
          return (this.result = null);
        case false:
          return (this.result = true);
      }
    },
    clickFailure () {
      switch (this.result) {
        case null:
          return (this.result = false);
        case true:
          return (this.result = false);
        case false:
          return (this.result = null);
      }
    },
    onClickCreate () {
      this.$store.dispatch("form/onClickCreate");
    },    
    onClickCancel() {
      this.$store.dispatch("form/closeForm");
    },
    async submitForm(){
      this.$store.dispatch("form/closeForm");
      const hypothesis = {
        name : this.inputFormName,
        parent_uuid: this.hypothesis.uuid,
      }
      const createdHypothesis = await this.$store.dispatch("hypothesis/createHypothesis", hypothesis);
      // ゴール作成後の遷移先
      const url = "/hypothesis/" + createdHypothesis.hypothesis.uuid;
      if (this.apiStatus) {
        this.$router.push(url);
      }
    },
    edit(){
        this.$store.dispatch("hypothesis/editHypothesis", this.hypothesis);
    }
  },
};
</script>

<style scoped lang='sass'>
.hypothesisSubTitle
  font-size: 1rem

.spHypothesisSubTitle
  font-size: 14px
  height: 24px
  padding: 0 0 0 12px

.spCardStyle
  height: calc(100vh - 224px)
  position: relative
</style>