<template>
  <div class="text-center">
    <v-dialog v-model="isDisplay" width="500">
      <template v-slot:activator="{ on, attrs }">
        <!-- PC版追加ボタン -->
        <NewAdditionalCard :on="on" :attrs="attrs" />
        <!-- スマホ版追加ボタン -->
        <SpNewAdditionalBtn :on="on" :attrs="attrs" />
      </template>
      <!-- 追加のフォーム -->
      <ProjectNameInput :isDisplay="isDisplay"/>
    </v-dialog>
  </div>
</template>

<script>
import NewAdditionalCard from "./Cards/NewAddtionalCard.vue";
import SpNewAdditionalBtn from "./Buttons/SpNewAdditionalBtn.vue";
import ProjectNameInput from "./Forms/ProjectNameInput.vue";

export default {
  components: {
    NewAdditionalCard,
    SpNewAdditionalBtn,
    ProjectNameInput
  },
  data: () => ({
      on: true,
      attrs: true,
      isDisplay: false
  }),
};
</script>