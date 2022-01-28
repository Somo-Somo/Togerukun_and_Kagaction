<template>
  <v-container
    class="d-flex flex-column justify-center my-8 px-md-16"
    style="max-width: 900px"
    fluid
  >
    <div class="d-flex justify-space-between">
      <v-subheader class="pa-md-0" style="font-size: 1rem"
        ><p class="ma-0 font-weight-bold" color="grey darken-1">
          プロジェクト一覧
        </p></v-subheader
      >
      <v-icon size="24">mdi-plus-circle</v-icon>
    </div>
    <v-col v-for="card in cards" :key="card" :card="card" cols="12">
      <ProjectCards />
    </v-col>
  </v-container>
</template>

<script>
import ProjectCards from "../components/ProjectCard.vue";

export default {
  components: {
    ProjectCards,
  },
  data: () => ({
    cards: ["Today", "Yesterday"],
  }),
};
</script>