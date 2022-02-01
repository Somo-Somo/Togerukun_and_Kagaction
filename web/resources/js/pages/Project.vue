<template>
  <v-container
    class="d-flex flex-column py-6 my-md-2 px-md-16"
    style="max-width: 900px"
    fluid
  >
    <div
      class="d-flex justify-space-between"
      style="position: fixed; width: 772px"
      absolute
    >
      <v-subheader class="pa-md-0 d-flex" style="font-size: 1rem">
        <p class="ma-0 font-weight-bold" color="grey darken-1">
          プロジェクト一覧
        </p>
      </v-subheader>
      <v-icon size="24">mdi-plus-circle</v-icon>
    </div>
      <Cards />
    <div class="text-center">
      <v-dialog v-model="inputForm" width="500">
        <template v-slot:activator="{ on, attrs }">
          <!-- PC版追加ボタン -->
          <NewAdditionalCard :on="on" :attrs="attrs" />
          <!-- スマホ版追加ボタン -->
          <SpNewAdditionalBtn :on="on" :attrs="attrs" />
        </template>
        <!-- 追加のフォーム -->
        <ProjectNameInput @clickCancel="isDisplay" @clickNext="isDisplay" />
      </v-dialog>
    </div>
  </v-container>
</template>

<script>
import Cards from "../components/Cards.vue";
import NewAdditionalCard from "../components/Cards/NewAddtionalCard.vue";
import SpNewAdditionalBtn from "../components/Buttons/SpNewAdditionalBtn.vue";
import ProjectNameInput from "../components/Forms/ProjectNameInput.vue";

export default {
  components: {
    Cards,
    NewAdditionalCard,
    SpNewAdditionalBtn,
    ProjectNameInput,
  },
    data: () => ({
    on: true,
    attrs: true,
    inputForm: false,
  }),
  methods: {
    isDisplay : function () {
      this.inputForm = !this.inputForm
    }
  },
};
</script>