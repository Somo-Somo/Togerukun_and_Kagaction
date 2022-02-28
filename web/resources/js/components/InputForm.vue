<template>
  <v-card ref="form">
        <v-card-text class="d-flex align-content-center px-9 pt-9 pb-0">
          <v-text-field
            class="pa-0 ma-0"
            v-model="name"
            :label="addingCard.category"
            clearable
          ></v-text-field>
        </v-card-text>
    <v-divider></v-divider>
    <v-card-actions>
      <v-btn @click="$emit('clickCancel')" text>
        キャンセル
      </v-btn>
      <v-spacer></v-spacer>
      <v-slide-x-reverse-transition> </v-slide-x-reverse-transition>
      <v-btn
        type="submit"
        :disabled="!formIsValid"
        color="primary"
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
        this.$store.dispatch("form/setName", value);
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