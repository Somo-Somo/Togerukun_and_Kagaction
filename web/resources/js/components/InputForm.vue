<template>
<v-dialog v-model="inputForm" @click:outside="$emit('onClickCancel')" width="500">
  <v-card ref="form">
        <v-card-text class="d-flex align-content-center px-9 pt-9 pb-0">
          <v-text-field
            class="pa-0 ma-0"
            v-model="formName"
            counter="64"
            :label="category"
            clearable
          ></v-text-field>
        </v-card-text>
    <v-divider></v-divider>
    <v-card-actions>
      <v-btn @click="$emit('onClickCancel')" text>
        キャンセル
      </v-btn>
      <v-spacer></v-spacer>
      <v-slide-x-reverse-transition> </v-slide-x-reverse-transition>
      <v-btn
        type="submit"
        :disabled="!formName"
        color="primary"
        @click="$emit('submitForm')"
        text
      >
        完了
      </v-btn>
    </v-card-actions>
  </v-card>
</v-dialog>
</template>


<script>
export default {
  data: () => ({

  }),
  props: {
    category: {
      type: String,
    },
    inputForm: {
      type: Boolean,
    },
    hypotheses: {
      type: Object,
    },
  },
  computed: {
    formName: {
      get () {
        return this.$store.getters['form/name']
      },
      set (value) {
        this.$store.dispatch("form/setName", value);
      }
    },
 
  },
  methods: {
   
  },
  watch: {
    // ダイアログが閉じた後フォームの値を全て空にする * computedに移行したい
    inputForm(inputForm) {
      if (!inputForm) {
        this.formName = "";
      }
    },
  },
};
</script>