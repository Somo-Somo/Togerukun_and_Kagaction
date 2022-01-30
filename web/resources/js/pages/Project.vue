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
    <!-- PC版 -->
    <div
      class="hidden-sm-and-down overflow-y-auto"
      style="height: calc(100vh - 144px); position: relative; top: 48px"
    >
      <Cards />
       
    </div>
    <!-- スマホ版 -->
    <div
      class="d-flex d-md-none justify-center overflow-y-auto"
      style="height: calc(100vh - 224px);  position: relative; top: 40px"
    >
      <Cards />
    </div>
    <AddCard  />
  </v-container>
</template>

<script>
import Cards from "../components/Cards.vue";
import AddCard from "../components/AddCard.vue";

export default {
  components: {
    Cards,
    AddCard
  },
};
</script>