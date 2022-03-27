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
      <div 
        v-if="!project && !hypothesis && !parentHypothesis"
        class="d-flex align-self-center px-1"
      >
        <h1 style="font-size: 20px">プロジェクト一覧</h1>
      </div>
      <div v-if="project" class="d-flex align-self-center">
        <v-btn 
          class="px-2" 
          @click="onClickHeaderTitle('project', project)" 
          text
        >
          <h1 style="font-size: 20px">{{ project.name }}</h1>
        </v-btn>
      </div>
      <div v-if="hypothesis" class="d-flex align-self-center">
        <h1 v-if="hypothesis.depth > 1" style="font-size: 20px">/</h1>
        <h1 v-if="hypothesis.depth > 1" style="font-size: 20px" class="px-2">...</h1>
      </div>
      <div v-if="parentHypothesis" class="d-flex align-self-center"> 
        <h1 style="font-size: 20px"> / </h1>
        <v-btn 
          class="px-2" 
          @click="onClickHeaderTitle('parentHypothesis', parentHypothesis)" 
          text
        >
          <h1 style="font-size: 20px" >{{ parentHypothesis.name }}</h1>
        </v-btn>
      </div>
      <div v-if="hypothesis" class="d-flex align-self-center" >
        <h1 style="font-size: 20px"> / </h1>
        <v-btn 
          class="px-2" 
          @click="onClickHeaderTitle('hypothesis', hypothesis)" 
          text
        >
          <h1 style="font-size: 20px" > {{ hypothesis.name }} </h1>
        </v-btn>
      </div>
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
    async onClickHeaderTitle(key, value){
      if (key === 'project') {
        await this.$store.dispatch("project/selectProject", value);
        this.$router.push({ path: "/project/" + value.uuid });
      } else if (key === 'parentHypothesis') {
        await this.$store.dispatch("hypothesis/selectHypothesis", value);
        this.$router.push({ path: "/hypothesis/" + value.uuid });
      }
    }
  },
};
</script>