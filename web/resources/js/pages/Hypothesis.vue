<template>
  <v-container
    class="d-flex flex-column py-6 my-md-2 px-md-16"
    :style="$vuetify.breakpoint.mdAndUp ? 'max-width: 900px' : ''"
    fluid
  >
    <div
      class="d-flex justify-space-between"
      style="position: fixed"
      :class="$vuetify.breakpoint.mdAndUp ? 'tabStyle' : 'spTabStyle'"
      absolute
    >
      <v-tabs class="" color="black" center-active>
        <v-tabs-slider color="#80CBC4"></v-tabs-slider>
        <v-tab>Purpose</v-tab>
        <v-tab>Today's Goal</v-tab>
        <v-tab>Issue</v-tab>
        <v-tab>Finished</v-tab>
      </v-tabs>
      <v-icon class="hidden-sm-and-down" size="24">mdi-plus-circle</v-icon>
    </div>
    <!-- PC版 -->
    <div>
      <v-dialog v-model="inputForm" width="500">
        <template v-slot:activator="{ on, attrs }">
          <div
            class="overflow-y-auto d-flex flex-column"
            :class="$vuetify.breakpoint.mdAndUp ? 'cardStyle' : 'spCardStyle'"
          >
            <Cards />
            <!-- PC版追加ボタン -->
            <NewAdditionalCard :on="on" :attrs="attrs" />
          </div>
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

<style scoped lang='sass'>
.tabStyle
  width: 772px

.spTabStyle
  width: 100vw
  left: -36px

.cardStyle
  height: calc(100vh - 152px)
  position: relative
  top: 48px

.spCardStyle
  height: calc(100vh - 224px)
  position: relative
  top: 56px
</style>