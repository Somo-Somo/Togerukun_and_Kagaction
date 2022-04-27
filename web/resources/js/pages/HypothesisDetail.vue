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
          :class="
            $vuetify.breakpoint.mdAndUp
              ? 'hypothesisDetailMain'
              : 'spHypothesisDetailMain'
          "
        >
          <div class="py-2 py-md-4 d-flex justify-start flex-column">
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
              class="pa-0 "
              rows="1"
              :class="$vuetify.breakpoint.smAndUp ? 'text-h5' : 'text-h6'"
              auto-grow
              single-line
              solo
              flat
              hide-details
            ></v-textarea>
          </div>
          <div class="d-flex">
            <div
              class="py-2 d-flex justify-start"
              :style="
                $vuetify.breakpoint.mdAndUp ? 'height: 72px' : 'height: 48px'
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
                <p class="ma-0 font-weight-bold" style="min-width:36px;" color="grey darken-1">完了：</p>
              </v-subheader>
              <v-col class="px-4 py-0 d-flex align-self-center">
                <v-checkbox
                  v-model="hypothesis.accomplish"
                  @click="onClickAccomplish(hypothesis.accomplish)"
                ></v-checkbox>
              </v-col>
            </div>
            <div
              class="py-2 ml-md-2 d-flex align-self-center"
              :style="
                $vuetify.breakpoint.mdAndUp ? 'height: 72px' : 'height: 48px'
              "
            >
              <v-subheader class="d-flex align-self-center pa-md-0">
                <p class="ma-0 font-weight-bold" color="grey darken-1">日付 :</p>
              </v-subheader>
              <v-col class="px-md-4 pa-0 d-flex align-self-center">
                <Calender :project="project"/>
              </v-col>
            </div>
          </div>
          <div class="pt-2">
            <div class="d-flex justify-space-between flex-column">    
              <v-tabs 
                v-model="tab" 
                class="px-0" 
                color="black" 
                :height="$vuetify.breakpoint.mdAndUp ? '' : '36'"
              >
                <v-tabs-slider color="#80CBC4"></v-tabs-slider>
                <v-tab
                  class="px-0"
                  v-for="kind in linkedToDo"
                  :key="kind.name"
                  :class="$vuetify.breakpoint.mdAndUp ? '' : 'spTabStyle'"
                >
                  <p class="ma-0 font-weight-bold">{{ kind.name }}</p>
                </v-tab>
              </v-tabs>
              <v-subheader
                v-if="tab === 0"
                class="px-md-0 mt-3 hypothesisSubTitle"
                v-show="$vuetify.breakpoint.smAndUp"
              >
                <p 
                  class="ma-0 font-weight-black caption align-self-center" 
                  color="grey lighten-1"
                  style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                  :style="$vuetify.breakpoint.smAndUp ? 'max-width: 70%' : 'max-width: 40vw'"
                >
                  「{{hypothesis.name}}
                </p>
                <p class="ma-0 font-weight-black caption align-self-center" color="grey lighten-1">
                  {{ assistSubHeaderText(tab) }}
                </p>
              </v-subheader>
            </div>
            <div
              class="overflow-y-auto d-flex flex-column"
              :class="$vuetify.breakpoint.mdAndUp ? 'cardStyle' : 'spCardStyle'"
            >
              <HypothesisCards 
                v-if="tab === 0"
               :project="project" 
               :selectHypothesis="hypothesis" 
               :hypothesisList="hypothesisList" 
               :hypothesisStatus="linkedToDo[0]" />
              <!-- PC版追加カード -->
              <NewAdditionalCard 
               @clickAditional="onClickCreate" 
               :category="linkedToDo[tab].name"/>
            </div>
          </div>
        </div>
        <!-- スマホ版追加ボタン -->
        <!-- <SpBottomBtn @clickAditional="onClickCreate" :headerTitle="page" /> -->
      </template>
      <form class="form" @submit.prevent="submitForm()">
        <InputForm
          v-if="form"
          @onClickCancel="onClickCancel"
          @submitForm="submitForm"
          :category="additionalInputFormLabel"
          :inputForm="inputForm"
          :loading="submitLoading" 
        />
      </form>
  </v-container>
</template>

<script>
import Header from "../components/Header.vue";
import Calender from "../components/Calender.vue";
import HypothesisCards from "../components/Cards/HypothesisCard.vue";
import NewAdditionalCard from "../components/Cards/NewAddtionalCard.vue";
import SpBottomBtn from "../components/Buttons/SpBottomBtn.vue";
import InputForm from "../components/InputForm.vue";
import { mapGetters, mapState } from 'vuex';

export default {
  components: {
    Header,
    Calender,
    HypothesisCards,
    NewAdditionalCard,
    SpBottomBtn,
    InputForm,
  },
  data: () => ({
    tab: 0,
    linkedToDo: [
      {name: "ToDo", show: false},
      {name: "コメント", show: false}
    ],
    submitLoading: false,
    form: false,
    date: null,
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
      return this.hypothesis.depth === 0 ? 'ゴール' : '｢'+ this.parentHypothesis.name +'｣ ためのToDo';
    },
    additionalInputFormLabel(){
      if (this.tab === 0) {
        return '「' +this.hypothesis.name + '」ためのToDo';
      } else {
        return 'コメント';
        
      }
    },
    assistSubHeaderText(){
      return (tab) => {
        if (tab === 0) {
          return '」を完了するには？'; 
        }
      }  
    },
  },
  methods: {
    onClickAccomplish (accomplish){
      this.$store.dispatch(
        "hypothesis/updateAccomplish",
         { accomplish:accomplish, hypothesisUuid:this.hypothesis.uuid }
      );
    },
    onClickCreate () {
      this.$store.dispatch("form/onClickCreate");
      this.form = true;
    },    
    onClickCancel() {
      this.$store.dispatch("form/closeForm");
      this.form = false;
    },
    submitForm(){
      this.$store.dispatch("form/closeForm");
      if (this.inputFormName) {
        this.$store.dispatch(
          "hypothesis/createHypothesis", 
          {parent: this.hypothesis, name: this.inputFormName}
        );
      }
      this.form = false;
    },
    editHypothesisName(){
      if (this.hypothesis.name) {
        this.$store.dispatch("hypothesis/editHypothesis", this.hypothesis);
      }
    },
  },
  watch: {
    $route (to, from) {
      //ブラウザバックで戻った先が再び仮説詳細ページだった場合
      if (to.params.id === this.parentHypothesis.uuid) {
        this.$store.dispatch("hypothesis/selectHypothesis", this.parentHypothesis);
      }
    },
    inputForm(inputForm) {
      if (!inputForm) {
        this.form = false;
      }
    },
  },
};
</script>

<style scoped lang='sass'>
.hypothesisDetailMain
  width: 772px
  position: fixed

.spHypothesisDetailMain
  width: calc(100vw - 24px)
  position: fixed
  top: 72px

.hypothesisSubTitle
  font-size: 1rem
  height: 36px

.spHypothesisSubTitle
  font-size: 12px
  height: 24px
  padding: 0 0 0 12px

.spTabStyle
  width: 25%
  height: 36px
  font-size: 0.75rem

.cardStyle
  height: calc(100vh - 376px)
  position: relative

.spCardStyle
  height: calc(100vh - 320px)
  position: relative
</style>