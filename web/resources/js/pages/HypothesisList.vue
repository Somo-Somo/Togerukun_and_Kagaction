<template>
  <v-container
    class="d-flex flex-column py-6 my-md-2 px-md-16"
    :style="$vuetify.breakpoint.mdAndUp ? 'max-width: 900px' : ''"
    fluid
  >
   <Header :headerTitle="project.name"/>
      <template>
        <div
          class="d-flex justify-space-between"
          style="position: fixed"
          :class="$vuetify.breakpoint.mdAndUp ? 'tabStyle' : 'spTabStyle'"
          absolute
        >
          <v-tabs v-model="tab" class="px-3 px-md-0" color="black" center-active>
            <v-tabs-slider color="#80CBC4"></v-tabs-slider>
            <v-tab
              v-for="tabName in tabs"
              :key="tabName"
            >
              <p class="ma-0 font-weight-bold">{{ tabName }}</p>
            </v-tab>
          </v-tabs>
          <v-icon
            v-if="tab === 0"
            class="hidden-sm-and-down my-3"
            size="24"
            height="24"
            @click="isDisplay()"
            >mdi-plus-circle</v-icon
          >
        </div>
        <!-- PC版 -->
        <div
          class="overflow-y-auto d-flex flex-column"
          :class="$vuetify.breakpoint.mdAndUp ? 'cardStyle' : 'spCardStyle'"
        >
          <v-tabs-items v-model="tab">
            <v-tab-item v-for="tabName in tabs" :key="tabName">
              <HypothesisCards :project="project" :hypothesisList="hypothesisList" :view="tabs[tab]" />
              <!-- PC版追加カード -->
              <NewAdditionalCard
                v-if="tab === 0"
                @clickAditional="onClickCreate"
                :category="tabs[0]"
              />
            </v-tab-item>
          </v-tabs-items>
        </div>
        <!-- スマホ版追加ボタン -->
        <SpBottomBtn @clickAditional="onClickCreate" :tab="tab" :headerTitle="'仮説一覧'" />
      </template>
      <form class="form" @submit.prevent="submitForm()">
        <InputForm
          @onClickCancel="onClickCancel"
          @submitForm="submitForm"
          :category="tabs[0]"
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
    tab: null,
    tabs: ["ゴール", "今日の目標", "仮説", "完了"],
  }),
  computed: {
    ...mapState({
      apiStatus: (state) => state.auth.apiStatus,
    }),
    ...mapGetters({
      name: 'form/name',
      inputForm: 'form/inputForm',
      project: 'project/project',
      hypothesisList: 'hypothesis/hypothesisList',
    })
  },
  methods: {
    onClickCreate () {
      this.$store.dispatch("form/onClickCreate");
    },  
    onClickCancel() {
      this.$store.dispatch("form/onClickCancel");
    },
    async submitForm(){      
      this.$store.dispatch("form/closeForm");
      await this.$store.dispatch(
        "hypothesis/createGoal", 
        {project: this.project, hypothesisName: this.name}
      ).then((result) => {
        console.info(result); 
      }).catch((err) => {
             console.info(err);     
      });

    }
  },
};
</script>

<style scoped lang='sass'>
.tabStyle
  width: 772px

.spTabStyle
  width: calc(100vw - 24px)

.cardStyle
  height: calc(100vh - 152px)
  position: relative
  top: 48px

.spCardStyle
  height: calc(100vh - 224px)
  position: relative
</style>

<style lang='sass'>
.v-slide-group__prev
  display: none !important
.v-slide-group__next
  display: none !important
</style>