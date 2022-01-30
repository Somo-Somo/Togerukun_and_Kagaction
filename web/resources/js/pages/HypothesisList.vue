<template>
  <v-container
    class="d-flex flex-column py-6 my-md-2 px-md-16"
    style="max-width: 900px"
    fluid
  >
    <div
      class="d-flex justify-space-between"
      style="position: fixed;"
    >
  <v-tabs class="" color="black"> 
    <v-tabs-slider color="#80CBC4"></v-tabs-slider>
    <v-tab>Purpose</v-tab>
    <v-tab>Today's Goal</v-tab>
    <v-tab>Issue</v-tab>
    <v-tab>Finished</v-tab>
  </v-tabs>
      <v-icon class="hidden-sm-and-down" size="24">mdi-plus-circle</v-icon>
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