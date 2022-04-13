<template>
  <v-app-bar
    class="my-1 mt-md-1 mb-md-0"
    :style="$vuetify.breakpoint.mdAndUp ? 'height: 72px' : 'height: 64px'"
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
      <div 
        v-if="!project && !hypothesis && !parentHypothesis"
        class="d-flex align-self-center px-1"
      >
        <h1 :style="$vuetify.breakpoint.mdAndUp ? 'font-size: 18px' : 'font-size: 16px'">
          {{ headerTitle }}
        </h1>
      </div>
      <div v-if="project" class="d-flex align-self-center">
        <v-btn 
          class="px-2" 
          @click="onClickHeaderTitle('project', project)" 
          text
        >
          <h1 :style="$vuetify.breakpoint.mdAndUp ? 'font-size: 18px' : 'font-size: 16px'">{{ project.name }}</h1>
        </v-btn>
      </div>
      <div v-if="hypothesis" class="d-flex align-self-center">
        <h1 
          v-if="hypothesis.depth > 1" 
          :style="$vuetify.breakpoint.mdAndUp ? 'font-size: 18px' : 'font-size: 16px'"
         >/</h1>
        <h1 
          v-if="hypothesis.depth > 1" 
          :style="$vuetify.breakpoint.mdAndUp ? 'font-size: 18px' : 'font-size: 16px'" 
          class="px-2"
         >...</h1>
      </div>
      <div v-if="parentHypothesis" class="d-flex align-self-center"> 
        <h1 :style="$vuetify.breakpoint.mdAndUp ? 'font-size: 18px' : 'font-size: 16px'" class="d-flex align-self-center"> / </h1>
        <v-btn 
          class="px-2" 
          @click="onClickHeaderTitle('hypothesis', parentHypothesis)" 
          text
        >
          <h1 :style="$vuetify.breakpoint.mdAndUp ? 'font-size: 18px' : 'font-size: 16px'" >{{ parentHypothesis.name }}</h1>
        </v-btn>
      </div>
      <div v-if="hypothesis" class="d-flex align-self-center" >
        <h1 :style="$vuetify.breakpoint.mdAndUp ? 'font-size: 18px' : 'font-size: 16px'" class="d-flex align-self-center"> / </h1>
        <v-btn 
          class="px-2" 
          @click="onClickHeaderTitle('hypothesis', hypothesis)" 
          text
        >
          <h1 :style="$vuetify.breakpoint.mdAndUp ? 'font-size: 18px' : 'font-size: 16px'" > 
            {{ hypothesis.name }} 
          </h1>
        </v-btn>
      </div>
    </v-toolbar-title>
  </v-app-bar>
</template> 

<script>
export default {
  props: {
    headerTitle: {
      type: String,
    },
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
  },
  methods: {
    clickHumburgerMenu() {
      this.$store.dispatch("navigation/changeNavState");
    },
    async onClickHeaderTitle(key, value){
      if (this.$route.params.id !== value.uuid) {
        if (key === 'project') {
          await this.$store.dispatch("project/selectProject", value);
          this.$router.push({ path: "/project/" + value.uuid });
        } else if (key === 'hypothesis') {
          await this.$store.dispatch("hypothesis/selectHypothesis", value);
          this.$router.push({ path: "/hypothesis/" + value.uuid });
        }        
      }
    }
  },
};
</script>