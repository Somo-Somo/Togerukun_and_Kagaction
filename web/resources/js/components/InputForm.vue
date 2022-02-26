<template>
  <v-card ref="form">
    <v-window v-model="step">
      <v-window-item :value="1">
        <v-card-text class="d-flex align-content-center px-9 pt-9 pb-0">
          <v-text-field
            class="pa-0 ma-0"
            v-model="name"
            :label="addingCard.category"
            clearable
          ></v-text-field>
        </v-card-text>
      </v-window-item>
      <v-window-item :value="2">
        <v-card-text class="d-flex flex-column">
          <v-card-subtitle class="pb-2 font-weight-bold">
            「{{ name }}」の上位目標を選択
          </v-card-subtitle>
          <v-card-subtitle class="pt-2 pb-4"
            >上位目標: {{ upperGoal.content }}</v-card-subtitle
          >
          <v-expansion-panels v-model="activeCategory">
            <v-expansion-panel
              v-for="(hypothesis, index) in hypotheses"
              @click="changeActiveListItem(index)"
              :key="index"
            >
              <div v-if="hypothesis.tab !== '完了'">
                <v-expansion-panel-header>
                  {{ hypothesis.tab }}{{ key }}
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                  <v-list>
                    <v-list-item-group v-model="activeHypothesis">
                      <v-list-item
                        v-for="(card, index) in hypothesis.cards"
                        @click="updateUpperGoalValue(index, card)"
                        :key="index"
                      >
                        <v-list-item-content>
                          <v-list-item-title v-text="card"></v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list-item-group>
                  </v-list>
                </v-expansion-panel-content>
              </div>
            </v-expansion-panel>
          </v-expansion-panels>
        </v-card-text>
      </v-window-item>
    </v-window>
    <v-divider></v-divider>
    <v-card-actions>
      <v-btn v-if="step === 1" @click="$emit('clickCancel')" text>
        キャンセル
      </v-btn>
      <v-btn v-else-if="step === 2" @click="step--" text> 戻る </v-btn>
      <v-spacer></v-spacer>
      <v-slide-x-reverse-transition> </v-slide-x-reverse-transition>
      <v-btn
        :disabled="!name"
        v-if="step === 1 && checkNameInputOnly === false"
        @click="step++"
        color="primary"
        text
      >
        次へ
      </v-btn>
      <v-btn
        type="submit"
        :disabled="!formIsValid"
        color="primary"
        v-else-if="step === 2 || checkNameInputOnly === true"
        @click="$emit('submitForm')"
        text
      >
        完了
      </v-btn>
    </v-card-actions>
  </v-card>
</template>


<script>
export default {
  data: () => ({
    step: 1,
    upperGoal: {
      category: "",
      index: "",
      content: "",
    },
    nameInputOnly: true,
    activeCategory: "",
    activeHypothesis: "",
  }),
  props: {
    addingCard: {
      type: Object,
    },
    dialog: {
      type: Boolean,
    },
    hypotheses: {
      type: Object,
    },
  },
  computed: {
    name: {
      get () {
        return this.$store.getters['form/name']
      },
      set (value) {
        this.$store.commit('form/setName', value)
      }
    },

    // 名前入力のみのフォームかチェック
    checkNameInputOnly() {
      if (
        this.addingCard.category === "ゴール" ||
        this.addingCard.category === "プロジェクト"
      ) {
        return true;
      } else {
        return false;
      }
    },

    // フォームが空じゃないかバリデーションチェック
    formIsValid() {
      if (
        this.addingCard.category === "ゴール" ||
        this.addingCard.category === "プロジェクト"
      ) {
        return this.name;
      } else {
        return (
          this.name &&
          this.upperGoal.content
        );
      }
    },
  },
  methods: {
    // acitveなlist-itemを変更
    changeActiveListItem: function (activeCategoryIndex) {
      if (this.upperGoal.category === activeCategoryIndex) {
        return (this.activeHypothesis = this.upperGoal.index);
      } else {
        return (this.activeHypothesis = "");
      }
    },

    // 上位目標の更新
    updateUpperGoalValue: function (
      activeHypothesisIndex,
      activeHypothesisContent
    ) {
      if (
        this.upperGoal.category === this.activeCategory &&
        this.upperGoal.index === activeHypothesisIndex &&
        this.upperGoal.content === activeHypothesisContent
      ) {
        // 選択された上位目標を再度クリックした時
        return (
          (this.upperGoal.category = ""),
          (this.upperGoal.index = ""),
          (this.upperGoal.content = "")
        );
      } else {
        // 上位目標を選択した時
        return (
          (this.upperGoal.category = this.activeCategory),
          (this.upperGoal.index = activeHypothesisIndex),
          (this.upperGoal.content = activeHypothesisContent)
        );
      }
    },
  },
  watch: {
    // ダイアログが閉じた後フォームの値を全て空にする * computedに移行したい
    dialog(isDisplay) {
      if (!isDisplay) {
        this.step = 1;
        this.name = "";
        this.upperGoal.category = "";
        this.upperGoal.index = "";
        this.upperGoal.content = "";
        this.activeCategory = "";
        this.activeHypothesis = "";
      }
    },
  },
};
</script>