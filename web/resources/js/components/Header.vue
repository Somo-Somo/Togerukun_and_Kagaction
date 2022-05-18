<template>
  <v-app-bar
    class=""
    :style="$vuetify.breakpoint.mdAndUp ? 'height: 64px' : 'height: 56px'"
    color="white"
    elevation="0"
    app
  >
    <v-app-bar-nav-icon
      v-if="(!navigation || !onboarding ) && $vuetify.breakpoint.md"
      @click="clickHumburgerMenu"
      class="mt-3 hidden-sm-and-down"
    ></v-app-bar-nav-icon>
    <v-toolbar-title class="d-flex justify-start mt-3 px-0 ml-md-2">
      <div 
        v-if="!project && !todo && !parentTodo"
        class="d-flex align-self-center px-1"
      >
        <h1 :style="$vuetify.breakpoint.mdAndUp ? 'font-size: 18px' : 'font-size: 1rem'">
          {{ headerTitle }}
        </h1>
      </div>
      <div v-if="project" class="d-flex align-self-center">
        <v-btn 
          class="px-2" 
          @click="onClickHeaderTitle('project', project)" 
          text
        >
          <h1
            :class="todo ? 'omitHeaderTitle' : ''" 
            :style="$vuetify.breakpoint.mdAndUp ? 
              'font-size: 18px' : 'font-size: 16px'"
            >{{ project.name }}</h1>
        </v-btn>
      </div>
      <div v-if="todo" class="d-flex align-self-center">
        <h1 
          v-if="todo.depth > 1" 
          :style="$vuetify.breakpoint.mdAndUp ? 'font-size: 18px' : 'font-size: 16px'"
         >/</h1>
        <h1 
          v-if="todo.depth > 1" 
          :style="$vuetify.breakpoint.mdAndUp ? 'font-size: 18px' : 'font-size: 16px'" 
          class="px-2"
         >...</h1>
      </div>
      <div v-if="parentTodo" class="d-flex align-self-center"> 
        <h1 :style="$vuetify.breakpoint.mdAndUp ? 'font-size: 18px' : 'font-size: 16px'" class="d-flex align-self-center"> / </h1>
        <v-btn 
          class="px-2" 
          @click="onClickHeaderTitle('todo', parentTodo)" 
          text
        >
          <h1 
            class="omitHeaderTitle" 
            :style="$vuetify.breakpoint.mdAndUp ? 'font-size: 18px' : 'font-size: 16px'
            " >{{ parentTodo.name }}</h1>
        </v-btn>
      </div>
      <div v-if="todo" class="d-flex align-self-center" >
        <h1 :style="$vuetify.breakpoint.mdAndUp ? 'font-size: 18px' : 'font-size: 16px'" class="d-flex align-self-center"> / </h1>
        <v-btn 
          class="px-2" 
          @click="onClickHeaderTitle('todo', todo)" 
          text
        >
          <h1 
            class="omitHeaderTitle" 
            :style="$vuetify.breakpoint.mdAndUp ? 'font-size: 18px' : 'font-size: 16px'" 
          > 
            {{ todo.name }} 
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
    todo: {
      type: Object,
    },
    parentTodo: {
      type: Object,
    },
  },
  computed: {
    navigation() {
      return this.$store.getters['navigation/navigation'];
    },
    onborading() {
      return this.$store.getters['onboarding/onboarding'];
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
        } else if (key === 'todo') {
          await this.$store.dispatch("todo/selectTodo", value);
          this.$router.push({ path: "/todo/" + value.uuid });
        }        
      }
    }
  },
};
</script>
<style scoped lang='sass'>
.omitHeaderTitle
  max-width:30vw
  white-space: nowrap 
  overflow: hidden 
  text-overflow: ellipsis
</style>