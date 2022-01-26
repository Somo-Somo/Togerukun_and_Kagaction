<template>
  <v-app id="inspire">
    <v-main>
      <v-container class="py-8 px-6" fluid>
        <v-col v-for="card in cards" :key="card" :card="card" cols="12">
          <ProjectCards />
        </v-col>
      </v-container>
    </v-main>
  </v-app>
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