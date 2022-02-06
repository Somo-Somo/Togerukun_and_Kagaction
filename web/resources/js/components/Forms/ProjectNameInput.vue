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
        <v-card-text class="d-flex align-content-center px-9 pt-9 pb-0">
          <v-expansion-panels>
            <v-expansion-panel
              v-for="hypothesis in hypotheses"
              :key="hypothesis.tab"
            >
            <div v-if="hypothesis.tab !== 'DONE'">
              <v-expansion-panel-header>
                {{ hypothesis.tab }}
              </v-expansion-panel-header>
              <v-expansion-panel-content>
                <v-simple-table>
                  <template v-slot:default>
                    <tbody>
                      <tr v-for="card in hypothesis.cards" :key="card">
                        <td>{{ card }}</td>
                      </tr>
                    </tbody>
                  </template>
                </v-simple-table>
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
    secondStepForm: "",
    nameInputOnly: true,
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
        return this.firstStepForm && this.secondStepForm;
      }
    },
  },
  watch: {
    inputForm(isDisplay) {
      if (!isDisplay) {
        this.firstStepForm = "";
        this.secondStepForm = "";
      }
    },
  },
};
</script>