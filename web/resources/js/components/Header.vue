<template>
  <v-app-bar
    class="my-3 mt-md-1 mb-md-0"
    style="height: 72px"
    color="white"
    elevation="0"
    app
  >
    <v-app-bar-nav-icon
      v-if="!navigation || $vuetify.breakpoint.md"
      @click="clickHumburgerMenu"
      class="mt-3 hidden-sm-and-down"
    ></v-app-bar-nav-icon>
    <v-toolbar-title class="d-flex justify-start mt-3 px-0 ml-md-2">
      <v-btn
        class="d-flex align-self-center"
        v-if="thisPageParamsId"
        @click="isBack()"
        small
        icon
        link
      >
        <v-icon
          class="px-2"
          >mdi-chevron-left</v-icon
        >
      </v-btn>
      <h1 
        class="px-2 d-flex align-self-center" 
        style="font-size: 24px"
        v-if="!project && !hypothesis && !parentHypothesis"
      >
        プロジェクト一覧
      </h1>
      <h1 
        class="px-2 d-flex align-self-center" 
        style="font-size: 20px"
        v-if="project"
      >
        {{ project.name || null }} 
      </h1>
      <div v-if="hypothesis">
        <h1 v-if="hypothesis.depth > 1">/...</h1>
      </div>
      <h1 
        class="px-2 d-flex align-self-center" 
        style="font-size: 20px"
        v-if="parentHypothesis"
      >
        / {{ parentHypothesis.name }}
      </h1>
      <h1 
        class="px-2 d-flex align-self-center" 
        style="font-size: 20px"
        v-if="hypothesis"
        >
        / {{ hypothesis.name }} 
      </h1>
    </v-toolbar-title>
  </v-app-bar>
</template> 

<script>
export default {
  props: {
    project: {
      type: Object,
    },
    hypothesis: {
      type: Object,
    },
    parentHypothesis: {
      type: Object,
    },
  },
  computed: {
    navigation() {
      return this.$store.getters['navigation/navigation'];
    },
    thisPageParamsId() {
      return this.$route.params.id;
    },
  },
  methods: {
    clickHumburgerMenu() {
      this.$store.dispatch("navigation/changeNavState");
    },
    isBack (headerTitle) {
      if (this.$route.name === "hypothesisList") {
        this.$router.push({ path: "/projects" });
      } else if (this.$route.name === "hypothesisDetail") {
        this.$router.back();
      }
    },
  },
};
</script>