<template>
  <v-container
    class="d-flex flex-column py-6 my-md-2 px-md-16"
    :style="$vuetify.breakpoint.mdAndUp ? 'max-width: 900px' : ''"
    fluid
  >
    <v-dialog v-model="dialog" width="500">
      <template v-slot:activator="{ on, attrs }">
        <div
          class="d-flex justify-space-between"
          style="position: fixed"
          :class="$vuetify.breakpoint.mdAndUp ? 'tabStyle' : 'spTabStyle'"
          absolute
        >
          <v-tabs v-model="tab" class="px-3 px-md-0" color="black" center-active>
            <v-tabs-slider color="#80CBC4"></v-tabs-slider>
            <v-tab
              v-for="hypothesis in hypotheses"
              :key="hypothesis.tab"
            >
              <p class="ma-0 font-weight-bold">{{ hypothesis.tab }}</p>
            </v-tab>
          </v-tabs>
          <v-icon
            v-if="tab === 0"
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
              <NewAdditionalCard
                v-if="tab === 0"
                :on="on"
                :attrs="attrs"
                :category="hypothesis.category"
              />
            </v-tab-item>
          </v-tabs-items>
        </div>
        <!-- スマホ版追加ボタン -->
        <SpBottomBtn :on="on" :attrs="attrs" :tab="tab" :headerTitle="'仮説一覧'" />
      </template>
      <form class="form" @submit.prevent="submitForm()">
        <InputForm
          @clickCancel="isDisplay"
          @clickNext="isDisplay"
          :addingCard="hypotheses[tab]"
          :dialog="dialog"
          :hypotheses="hypotheses"
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
    tab: null,
    purposeCards: ["テスト版リリース",],
    todaysGoalCards: ["フロントエンドを完成させる", "neo4jDBと接続"],
    issueCards: ["フロントエンドを完成させる", "neo4jDBと接続", "デプロイの仕方がわからない", "グラフDBの設計"],
    finishedCards: ["全体設計", "figmaでデザイン"],
    hypotheses: [
      { tab: "ゴール", cards: ["テスト版リリース"], category: "ゴール" },
      {
        tab: "今日の目標",
        cards: ["フロントエンドを完成させる", "neo4jDBと接続"],
        category: "今日の目標",
      },
      {
        tab: "仮説",
        cards: ["フロントエンドを完成させる", "neo4jDBと接続", "デプロイの仕方がわからない", "グラフDBの設計"],
        category: "仮説",
      },
      { tab: "完了", cards: ["全体設計", "figmaでデザイン"], category: "完了" },
    ],
  }),
  computed: {
    ...mapState({
      apiStatus: (state) => state.auth.apiStatus,
    }),
    ...mapGetters({
      name: 'form/name',
      project: 'project/project',
    })
  },
  methods: {
    isDisplay: function () {
      this.dialog = !this.dialog;
    },
    async submitForm(){
      const hypothesis = {
        name : this.name,
        parent_uuid: this.project.uuid,
      }
      console.info(this.project);
      this.dialog = !this.dialog;
      const createdGoal = await this.$store.dispatch("hypothesis/createGoal", hypothesis);

      const url = "hypothesis/" + createdGoal.uuid;
      
      if (this.apiStatus) {
        this.$router.push(url);
      }
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