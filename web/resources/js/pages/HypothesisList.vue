<template>
  <v-container
    class="d-flex flex-column px-0 px-md-16"
    :style="$vuetify.breakpoint.mdAndUp ? 'max-width: 900px' : ''"
    fluid
  >
   <Header :project="project"/>
      <template>
        <div
          class="d-flex justify-space-between"
          :class="$vuetify.breakpoint.mdAndUp ? 'tabsStyle' : 'spTabsStyle'"
        >
          <v-tabs 
            v-model="tab" 
            class="px-md-0" 
            color="black" 
            :height="$vuetify.breakpoint.mdAndUp ? '' : '40'"
            >
            <v-tabs-slider color="#80CBC4"></v-tabs-slider>
            <v-tab
              class="px-0"
              v-for="hypothesisStatus in hypothesisStatuses"
              :key="hypothesisStatus.name"
              :class="$vuetify.breakpoint.mdAndUp ? 'tabStyle' : 'spTabStyle'"
            >
              <p class="ma-0 font-weight-bold">{{ hypothesisStatus.name }}</p>
            </v-tab>
          </v-tabs>
          <v-icon
            v-if="tab === 0"
            class="hidden-sm-and-down my-3"
            size="24"
            height="24"
            @click="onClickCreate()"
            >mdi-plus-circle</v-icon
          >
        </div>
        <v-divider style="position:relative; top:92px;"></v-divider>
        <!-- PC版 -->
        <div
          class="d-flex flex-column px-2 px-md-0"
          :class="$vuetify.breakpoint.mdAndUp ? 'cardStyle' : 'spCardStyle'"
        >
          <v-tabs-items class="overflow-y-auto" v-model="tab">
            <v-tab-item v-for="hypothesisStatus in hypothesisStatuses" :key="hypothesisStatus.name">
              <HypothesisCards
              :project="project" 
              :hypothesisList="hypothesisList" 
              :hypothesisStatus="hypothesisStatuses[tab]" 
              />
              <!-- PC版追加カード -->
              <NewAdditionalCard
                v-if="tab === 0"
                @clickAditional="onClickCreate"
                :category="hypothesisStatus.name"
              />
                    
            </v-tab-item>
          </v-tabs-items>
        </div>
        <!-- スマホ版追加ボタン -->
        <!-- <SpBottomBtn 
          v-if="tab === 0"
          @clickAditional="onClickCreate" 
          :tab="tab" 
          :headerTitle="'仮説一覧'" 
        /> -->
      </template>
      <form class="form" @submit.prevent="submitForm()">
        <InputForm
          @onClickCancel="onClickCancel"
          @submitForm="submitForm"
          :category="hypothesisStatuses[0].name"
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
    hypothesisStatuses: [
      {name : "ゴール", show: false},
      {name : "仮説一覧", show: false},
      {name : "現在の目標", show: false}, 
      {name : "完了", show: false}
    ],
    show: false,
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
      this.$store.dispatch("form/closeForm");
    },
    submitForm(){      
      this.$store.dispatch("form/closeForm");
      this.$store.dispatch(
        "hypothesis/createGoal", 
        {project: this.project, hypothesisName: this.name}
      )
    }
  },
};
</script>

<style scoped lang='sass'>
.tabsStyle
  width: 772px
  position: absolute


.spTabsStyle
  width: 100%
  height: 40px
  position: absolute
  top: 64px

.tabStyle

.spTabStyle
  width: 25%
  height: 40px
  font-size: 0.75rem

.cardStyle
  height: calc(100vh - 152px)
  position: relative
  top: 48px

.spCardStyle
  height: calc(100vh - 192px)
  position: relative
  top: 96px
</style>

<style lang='sass'>
.v-slide-group__prev
  display: none !important
.v-slide-group__next
  display: none !important
</style>