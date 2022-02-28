<template>
  <v-container
    class="d-flex flex-column py-6 my-md-2 px-md-16"
    :style="$vuetify.breakpoint.mdAndUp ? 'max-width: 900px' : ''"
    fluid
  >
    <v-dialog v-model="dialog" width="500">
      <template v-slot:activator="{ on, attrs }">
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
              v-model="title"
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
                v-bind="attrs"
                v-on="on"
                >mdi-plus-circle</v-icon
              >
            </div>
            <div
              class="overflow-y-auto d-flex flex-column"
              :class="$vuetify.breakpoint.mdAndUp ? 'cardStyle' : 'spCardStyle'"
            >
              <HypothesisCards :cards="cards" />
              <!-- PC版追加カード -->
              <NewAdditionalCard :on="on" :attrs="attrs" :category="category"/>
            </div>
          </div>
        </div>
        <!-- スマホ版追加ボタン -->
        <SpBottomBtn :on="on" :attrs="attrs" :headerTitle="'仮説詳細'" />
      </template>
      <form class="form" @submit.prevent="submitForm()">
        <InputForm
          @clickCancel="isDisplay"
          @clickNext="isDisplay"
          :category="category"
          :dialog="dialog"
        />
      </form>
    </v-dialog>
  </v-container>
</template>

<script>
import HypothesisCards from "../components/Cards/HypothesisCard.vue";
import NewAdditionalCard from "../components/Cards/NewAddtionalCard.vue";
import SpBottomBtn from "../components/Buttons/SpBottomBtn.vue";
import InputForm from "../components/InputForm.vue";
import { mapGetters, mapState } from 'vuex';

export default {
  components: {
    HypothesisCards,
    NewAdditionalCard,
    SpBottomBtn,
    InputForm,
  },
  data: () => ({
    on: true,
    attrs: true,
    dialog: false,
    category: "仮説",
    cards: ["グラフDB設計", "neo4jと接続"],
    result: null,
  }),
  computed : {
    ...mapState({
      hypothesis: (state) => state.hypothesis.hypothesis,
      apiStatus: (state) => state.auth.apiStatus,
    }),
   ...mapGetters({
      inputFormName: 'form/name',
    }),
    title: {
      get () {
        return this.$store.getters['hypothesis/hypothesisName']
      },
      set (value) {
        this.$store.dispatch("hypothesis/setInputName", value);
      }
    }
  },
  methods: {
    clickSuccess: function () {
      switch (this.result) {
        case null:
          return (this.result = true);
        case true:
          return (this.result = null);
        case false:
          return (this.result = true);
      }
    },
    clickFailure: function () {
      switch (this.result) {
        case null:
          return (this.result = false);
        case true:
          return (this.result = false);
        case false:
          return (this.result = null);
      }
    },
    isDisplay: function () {
      this.dialog = !this.dialog;
    },
    async submitForm(){
      const hypothesis = {
        name : this.inputFormName,
        parent_uuid: this.hypothesis.uuid,
      }
      
      this.dialog = !this.dialog;
      const createdHypothesis = await this.$store.dispatch("hypothesis/createHypothesis", hypothesis);

      console.info(createdHypothesis.hypothesis.uuid);

      // ゴール作成後の遷移先
      const url = "/hypothesis/" + createdHypothesis.hypothesis.uuid;
      
      if (this.apiStatus) {
        this.$router.push(url);
      }
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