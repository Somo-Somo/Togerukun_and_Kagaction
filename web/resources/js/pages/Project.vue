<template>
  <v-container
    class="d-flex flex-column justify-center my-8 px-md-16"
    style="max-width: 900px"
    fluid
  >
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