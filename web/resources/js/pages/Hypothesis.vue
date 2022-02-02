<template>
  <v-container
    class="d-flex flex-column py-6 my-md-2 px-md-16"
    :style="$vuetify.breakpoint.mdAndUp ? 'max-width: 900px' : ''"
    fluid
  >
    <v-dialog v-model="inputForm" width="500">
      <template v-slot:activator="{ on, attrs }">
        <div
          class="d-flex justify-space-between"
          style="position: fixed"
          :class="$vuetify.breakpoint.mdAndUp ? 'tabStyle' : 'spTabStyle'"
          absolute
        >
          <v-tabs v-model="tab" color="black" center-active>
            <v-tabs-slider color="#80CBC4"></v-tabs-slider>
            <v-tab v-for="hypothesis in hypotheses" :key="hypothesis.tab">
              {{ hypothesis.tab }}
            </v-tab>
          </v-tabs>
          <v-icon
            class="hidden-sm-and-down my-3"
            size="24"
            height="24"
            v-bind="attrs"
            v-on="on"
            >mdi-plus-circle</v-icon
          >
        </div>
        <!-- PC版 -->
        <div
          class="overflow-y-auto d-flex flex-column"
          :class="$vuetify.breakpoint.mdAndUp ? 'cardStyle' : 'spCardStyle'"
        >
          <v-tabs-items v-model="tab">
            <v-tab-item v-for="hypothesis in hypotheses" :key="hypothesis.tab">
                <HypothesisCards :cards="hypothesis.cards" :tab="tab" />
                 <!-- PC版追加カード -->
                <NewAdditionalCard :on="on" :attrs="attrs" />
            </v-tab-item>
          </v-tabs-items>
        </div>
        <!-- スマホ版追加ボタン -->
        <SpNewAdditionalBtn :on="on" :attrs="attrs" />
      </template>
      <ProjectNameInput @clickCancel="isDisplay" @clickNext="isDisplay" />
    </v-dialog>
  </v-container>
</template>

<script>
import HypothesisCards from "../components/Cards/HypothesisCard.vue";
import NewAdditionalCard from "../components/Cards/NewAddtionalCard.vue";
import SpNewAdditionalBtn from "../components/Buttons/SpNewAdditionalBtn.vue";
import ProjectNameInput from "../components/Forms/ProjectNameInput.vue";

export default {
  components: {
    HypothesisCards,
    NewAdditionalCard,
    SpNewAdditionalBtn,
    ProjectNameInput,
  },
  data: () => ({
    on: true,
    attrs: true,
    inputForm: false,
    tab: null,
    purposeCards: ["天下統一","日本海制圧"],
    todaysGoalCards: ["aiueo", "kakikukeko"],
    issueCards: ["アイウエオ", "カキクケコ", "サシスセソ"],
    finishedCards: ["あいうえお", "かきくけこ"],
    hypotheses: [
      { tab: "PURPOSE", cards: ["天下統一","日本海制圧"] },
      { tab: "TODAY'S GOAL", cards: ["aiueo", "kakikukeko"] },
      { tab: "ISSUE", cards: ["アイウエオ", "カキクケコ", "サシスセソ"] },
      { tab: "FINISHED", cards: ["あいうえお", "かきくけこ"], },
    ],
  }),
  methods: {
    isDisplay: function () {
      this.inputForm = !this.inputForm;
    },
  },
};
</script>

<style scoped lang='sass'>
.tabStyle
  width: 772px

.spTabStyle
  width: 100vw
  left: -36px

.cardStyle
  height: calc(100vh - 152px)
  position: relative
  top: 48px

.spCardStyle
  height: calc(100vh - 224px)
  position: relative
</style>