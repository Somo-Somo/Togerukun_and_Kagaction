<template>
  <v-container
    class="d-flex flex-column py-6 my-md-2 px-md-16"
    style="max-width: 900px"
    fluid
  >
    <v-dialog v-model="dialog" width="500">
      <template v-slot:activator="{ on, attrs }">
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
          <v-icon
            class="hidden-sm-and-down my-3"
            size="24"
            height="24"
            v-bind="attrs"
            v-on="on"
            >mdi-plus-circle</v-icon
          >
        </div>

        <div
          class="overflow-y-auto d-flex flex-column"
          :class="$vuetify.breakpoint.mdAndUp ? 'cardStyle' : 'spCardStyle'"
        >
          <Cards />
          <!-- PC版追加カード -->
          <NewAdditionalCard :on="on" :attrs="attrs" :category="category" />
        </div>
        <!-- スマホ版追加ボタン -->
        
        <SpNewAdditionalBtn :on="on" :attrs="attrs" />
      
      </template>
      <!-- 追加のフォーム -->
      <InputForm @clickCancel="isDisplay" @clickNext="isDisplay" :addingForm="addingForm" />
    </v-dialog>
  </v-container>
</template>

<script>
import Cards from "../components/Cards.vue";
import NewAdditionalCard from "../components/Cards/NewAddtionalCard.vue";
import SpNewAdditionalBtn from "../components/Buttons/SpNewAdditionalBtn.vue";
import InputForm from "../components/InputForm.vue";

export default {
  components: {
    Cards,
    NewAdditionalCard,
    SpNewAdditionalBtn,
    InputForm,
  },
  data: () => ({
    on: true,
    attrs: true,
    dialog: false,
    addingForm: {category: "プロジェクト"},
    
  }),
  methods: {
    isDisplay: function () {
      this.dialog = !this.dialog;
    },
  },
};
</script>

<style scoped lang='sass'>
.cardStyle
  height: calc(100vh - 152px)
  position: relative
  top: 48px

.spCardStyle
  height: calc(100vh - 224px)
  position: relative
</style>