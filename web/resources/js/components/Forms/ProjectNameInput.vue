<template>
  <v-card ref="form">
    <v-window v-model="step">
      <v-window-item :value="1">
        <v-card-text class="d-flex align-content-center px-9 pt-9 pb-0">
          <v-text-field
            class="pa-0 ma-0"
            v-model="firstStepForm"
            :label="addingForm.category"
            clearable
          ></v-text-field>
        </v-card-text>
      </v-window-item>
      <v-window-item :value="2">
        <v-card-text class="d-flex flex-column">
          <v-card-subtitle>
            「{{ firstStepForm }}」の上位目標を選択
          </v-card-subtitle>
          <v-card-subtitle
            >上位目標: {{ secondStepForm.upperGoal.content }}</v-card-subtitle
          >
          <v-expansion-panels v-model="selectedCategory">
            <v-expansion-panel
              v-for="(hypothesis, index) in hypotheses"
              @click="updateSelectedHypothesis(index)"
              :key="index"
            >
              <div v-if="hypothesis.tab !== 'DONE'">
                <v-expansion-panel-header>
                  {{ hypothesis.tab }}{{ key }}
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                  <v-list>
                    <v-list-item-group v-model="selectedHypothesis">
                      <v-list-item
                        v-for="(card, index) in hypothesis.cards"
                        @click="updateSecondStepForm(index, card)"
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
        :disabled="!firstStepForm"
        v-if="step === 1 && checkNameInputOnly === false"
        @click="step++"
        color="primary"
        text
      >
        次へ
      </v-btn>
      <v-btn
        :disabled="!secondStepFormIsValid"
        color="primary"
        v-else-if="step === 2 || checkNameInputOnly === true"
        @click="$emit('clickNext')"
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
    firstStepForm: "",
    secondStepForm: {
      category: "",
      upperGoal: {
        index: "",
        content: "",
      },
    },
    nameInputOnly: true,
    selectedCategory: "",
    selectedHypothesis: "",
  }),
  props: {
    addingForm: {
      type: Object,
    },
    inputForm: {
      type: Boolean,
    },
    hypotheses: {
      type: Object,
    },
  },
  computed: {
    checkNameInputOnly() {
      if (
        this.addingForm.category === "ゴール" ||
        this.addingForm.category === "プロジェクト"
      ) {
        return true;
      } else {
        return false;
      }
    },
    secondStepFormIsValid() {
      if (
        this.addingForm.category === "ゴール" ||
        this.addingForm.category === "プロジェクト"
      ) {
        return this.firstStepForm;
      } else {
        return (
          this.firstStepForm &&
          this.secondStepForm.category &&
          this.secondStepForm.upperGoal.content
        );
      }
    },
  },
  methods: {
    updateSelectedHypothesis: function (selectedCategoryIndex) {
      if (this.secondStepForm.category === selectedCategoryIndex) {
        return (this.selectedHypothesis = this.secondStepForm.upperGoal.index);
      } else {
        return (this.selectedHypothesis = "");
      }
    },
    updateSecondStepForm: function (
      selectedHypothesisIndex,
      selectedHypothesisContent
    ) {
      if (
        this.secondStepForm.category === this.selectedCategory &&
        this.secondStepForm.upperGoal.index === selectedHypothesisIndex &&
        this.secondStepForm.upperGoal.content === selectedHypothesisContent
      ) {
        // 選択された上位目標を再度クリックした時
        return (
          (this.secondStepForm.category = ""),
          (this.secondStepForm.upperGoal.index = ""),
          (this.secondStepForm.upperGoal.content = "")
        );
      } else {
        // 上位目標を選択した時
        return (
          (this.secondStepForm.category = this.selectedCategory),
          (this.secondStepForm.upperGoal.index = selectedHypothesisIndex),
          (this.secondStepForm.upperGoal.content = selectedHypothesisContent)
        );
      }
    },
  },
  watch: {
    inputForm(isDisplay) {
      if (!isDisplay) {
        this.step = 1;
        this.firstStepForm = "";
        this.secondStepForm.category = "";
        this.secondStepForm.upperGoal.index = "";
        this.secondStepForm.upperGoal.content = "";
      }
    },
  },
};
</script>