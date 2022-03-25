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
              <p class="ma-0 font-weight-bold" color="grey darken-1">{{ subHeader }}</p>
            </v-subheader>
            <v-textarea
              label="subHeader + 'を入力'"
              v-model="hypothesis.name"
              @change="edit"
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
              <p class="ma-0 font-weight-bold" color="grey darken-1">結果:</p>
            </v-subheader>
            <v-col class="px-4 px-md-6 d-flex align-self-center">
              <v-btn
                class="mx-1"
                @click="onClickStatus('success')"
                :color="result === 'success' ? 'green' : ''"
                size="36"
                icon
                text
              >
                <v-icon size="32">mdi-check-circle-outline</v-icon>
              </v-btn>
              <v-btn
                class="mx-1"
                @click="onClickStatus('failure')"
                :color="result === 'failure' ? 'pink' : ''"
                size="36"
                icon
                text
              >
                <v-icon size="32">mdi-close-circle-outline</v-icon>
              </v-btn>
            </v-col>
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
              <p class="ma-0 font-weight-bold" color="grey darken-1">現在の目標:</p>
            </v-subheader>
            <v-col class="px-4 px-md-6 d-flex align-self-center">
              <v-checkbox
                v-model="todaysGoal"
                @click="onClickTodaysGoal(todaysGoal)"
              ></v-checkbox>
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
    hypothesisStatus: {name: "仮説", show: false },
    page: "仮説",
    result: undefined,
    todaysGoal: undefined,
  }),
  computed : {
    ...mapState({
      hypothesis: (state) => state.hypothesis.hypothesis,
      apiStatus: (state) => state.auth.apiStatus,
    }),
   ...mapGetters({
      inputFormName: 'form/name',
      inputForm: 'form/inputForm',
      project: 'project/project',
      hypothesisList: 'hypothesis/hypothesisList',
    }),
    hypothesis() {
        let getterHypothesis = this.$store.getters['hypothesis/hypothesis'];
        if(this.result === undefined) this.result = getterHypothesis.status;
        if(this.todaysGoal === undefined) this.todaysGoal = getterHypothesis.todaysGoal;
        return getterHypothesis;
    },
    subHeader() {
      return this.hypothesis.depth === 0 ? 'ゴール' : '仮説';
    },
    additionalInputFormLabel(){
      return '「' +this.hypothesis.name + '」の仮説';
    }
  },
  methods: {
    onClickStatus (btn){
      let click;
      if (btn === 'success') {
        click = this.result === 'success' ?  'remove'  : 'success';
        this.result = this.result === 'success' ? null : 'success';
      } else if (btn === 'failure') {
        click = this.result === 'failure' ?  'remove'  : 'failure';
        this.result = this.result === 'failure' ? null : 'failure'; 
      }
      this.$store.dispatch(
        "hypothesis/updateStatus", 
        { click:click, hypothesisUuid:this.hypothesis.uuid }
      );
    },
    onClickTodaysGoal (todaysGoal){
      this.$store.dispatch(
        "hypothesis/updateTodaysGoal",
         { todaysGoal:todaysGoal, hypothesisUuid:this.hypothesis.uuid }
      );
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
      await this.$store.dispatch("hypothesis/createHypothesis", hypothesis);
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

.cardStyle
  height: calc(100vh - 400px)
  position: relative

.spCardStyle
  height: calc(100vh - 224px)
  position: relative
</style>