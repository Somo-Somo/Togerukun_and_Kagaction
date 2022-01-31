<template>
  <div class="text-center">
    <v-dialog v-model="cardForm" width="500">
      <template v-slot:activator="{ on, attrs }">
        <!-- PC版追加ボタン -->
        <NewAdditionalCard :on="on" :attrs="attrs" />
        <!-- スマホ版追加ボタン -->
        <SpNewAdditionalBtn :on="on" :attrs="attrs" />
      </template>
      <!-- 追加のフォーム -->
      <v-card ref="form">
        <v-card-text class="d-flex align-content-center px-9 pt-9 pb-0">
          <v-text-field
            class="pa-0 ma-0"
            v-model="message1"
            label="プロジェクト名"
            clearable
          ></v-text-field>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-btn @click="cardForm = false" text> Cancel </v-btn>
          <v-spacer></v-spacer>
          <v-slide-x-reverse-transition> </v-slide-x-reverse-transition>
          <v-btn color="primary" @click="cardForm = false" text> Next </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import NewAdditionalCard from "./Cards/NewAddtionalCard.vue";
import NewAdditionalCard from "./Buttons/SpNewAdditionalBtn.vue";
import SpNewAdditionalBtn from "./SpNewAdditionalBtn.vue";

export default {
  components: {
    NewAdditionalCard,
    SpNewAdditionalBtn,
    SpNewAdditionalBtn,
  },
  data() {
    return {
      on: true,
      attrs: true,
    };
  },
  props: {
    cardForm: {
      type: Boolean,
    },
  },
};
</script>