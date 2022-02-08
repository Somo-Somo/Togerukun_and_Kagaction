<template>
  <v-app-bar
    class="my-3 mt-md-1 mb-md-0"
    style="height: 72px"
    color="white"
    elevation="0"
    app
  >
    <v-app-bar-nav-icon
      v-if="!drawer || $vuetify.breakpoint.md"
      @click="$emit('clickMenu')"
      class="mt-3 hidden-sm-and-down"
    ></v-app-bar-nav-icon>
    <v-toolbar-title class="d-flex justify-start mt-3 px-0 ml-md-2">
      <v-btn
        class="d-flex align-self-center"
        :to="headerTitle === '仮説一覧' ? '/project' : '/project/123'"
        exact
        small
        icon
        link
      >
        <v-icon
          class="px-2"
          v-if="headerTitle === '仮説一覧' || headerTitle === '仮説詳細'"
          >mdi-chevron-left</v-icon
        >
      </v-btn>
      <h1 class="px-2 d-flex align-self-center" style="font-size: 24px">
        {{ headerTitle }}
      </h1>
    </v-toolbar-title>
  </v-app-bar>
</template> 

<script>
export default {
  props: {
    drawer: {
      type: Boolean,
    },
    headerTitle: {
      type: String,
    },
  },
};
</script>