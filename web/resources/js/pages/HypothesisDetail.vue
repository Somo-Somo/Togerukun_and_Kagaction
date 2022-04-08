<template>
  <v-container
    class="d-flex flex-column py-0 my-md-2 px-md-16"
    :style="$vuetify.breakpoint.mdAndUp ? 'max-width: 900px' : ''"
    fluid
  >
     <Header 
      :project="project"
      :hypothesis="hypothesis"
      :parentHypothesis="parentHypothesis"
      />
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
          <div class="py-4 d-flex justify-start flex-column">
            <v-subheader
              class="pa-md-0 d-flex"
              :class="
                $vuetify.breakpoint.mdAndUp
                  ? 'hypothesisSubTitle'
                  : 'spHypothesisSubTitle'
              "
            >
              <p class="ma-0 font-weight-bold" color="grey darken-1">{{ subHeader }}</p>
            </v-subheader>
            <v-textarea
              label="名前を入力"
              v-model="hypothesis.name"
              @change="editHypothesisName"
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
              $vuetify.breakpoint.mdAndUp ? 'height: 72px' : 'height: 64px'
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
              <p class="ma-0 font-weight-bold" color="grey darken-1">完了：</p>
            </v-subheader>
            <v-col class="px-4 py-0 d-flex align-self-center">
              <v-checkbox
                v-model="hypothesis.accomplish"
                @click="onClickAccomplish(hypothesis.accomplish)"
              ></v-checkbox>
            </v-col>
          </div>
          <div
            class="py-2 d-flex justify-start"
            :style="
              $vuetify.breakpoint.mdAndUp ? 'height: 72px' : 'height: 64px'
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
              <p class="ma-0 font-weight-bold" color="grey darken-1">現在の目標：</p>
            </v-subheader>
            <v-col class="px-4 py-0 d-flex align-self-center">
              <v-checkbox
                v-model="hypothesis.currentGoal"
                @click="onClickCurrentGoal(hypothesis.currentGoal)"
              ></v-checkbox>
            </v-col>
          </div>
          <div class="py-4">
            <div class="d-flex justify-space-between">
              <v-subheader
                class="pa-md-0"
                :class="
                  $vuetify.breakpoint.mdAndUp
                    ? 'hypothesisSubTitle'
                    : 'spHypothesisSubTitle'
                "
              >
                <p class="ma-0 font-weight-bold align-self-center" color="grey darken-1">仮説：</p>
                <p class="ma-0 font-weight-black caption align-self-center" color="grey lighten-1">
                  「{{hypothesis.name}}」を達成するためには？
                </p>
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
              <HypothesisCards 
               :project="project" 
               :selectHypothesis="hypothesis" 
               :hypothesisList="hypothesisList" 
               :hypothesisStatus="hypothesisStatus" />
              <!-- PC版追加カード -->
              <NewAdditionalCard 
               @clickAditional="onClickCreate" 
               :category="additionalInputFormLabel"/>
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
          :category="additionalInputFormLabel"
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
    hypothesisStatus: {name: "仮説", show: false},
    page: "仮説",
  }),
  computed : {
    ...mapState({
      apiStatus: (state) => state.auth.apiStatus,
    }),
   ...mapGetters({
      inputFormName: 'form/name',
      inputForm: 'form/inputForm',
      project: 'project/project',
      hypothesis: 'hypothesis/hypothesis',
      parentHypothesis: 'hypothesis/parentHypothesis',
      hypothesisList: 'hypothesis/hypothesisList',
    }),
    subHeader() {
      return this.hypothesis.depth === 0 ? 'ゴール' : '仮説';
    },
    additionalInputFormLabel(){
      return '「' +this.hypothesis.name + '」の仮説';
    }
  },
  methods: {
    onClickAccomplish (accomplish){
      this.$store.dispatch(
        "hypothesis/updateAccomplish",
         { accomplish:accomplish, hypothesisUuid:this.hypothesis.uuid }
      );
    },
    onClickCurrentGoal (currentGoal){
      this.$store.dispatch(
        "hypothesis/updateCurrentGoal",
         { currentGoal:currentGoal, hypothesisUuid:this.hypothesis.uuid }
      );
    },
    onClickCreate () {
      this.$store.dispatch("form/onClickCreate");
    },    
    onClickCancel() {
      this.$store.dispatch("form/closeForm");
    },
    submitForm(){
      this.$store.dispatch("form/closeForm");
      if (this.inputFormName) {
        this.$store.dispatch(
          "hypothesis/createHypothesis", 
          {parent: this.hypothesis, name: this.inputFormName}
        );
      }
    },
    editHypothesisName(){
      if (this.hypothesis.name) {
        this.$store.dispatch("hypothesis/editHypothesis", this.hypothesis);
      }
    },
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

.cardStyle
  height: calc(100vh - 400px)
  position: relative

.spCardStyle
  height: calc(100vh - 224px)
  position: relative
</style>