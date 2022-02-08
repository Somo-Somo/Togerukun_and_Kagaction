<template>
  <v-app-bar class="my-3 my-md-0" style="height: 72px" color="white" elevation="0" app>
    <v-app-bar-nav-icon
      v-if="!drawer"
      @click="$emit('clickMenu')"
      class="mt-3 hidden-sm-and-down"
    ></v-app-bar-nav-icon>
    <v-toolbar-title class="mt-3 px-0 ml-md-2">
        <h1 class="d-flex justify-start" style="font-size:24px">{{ headerTitle }}{{drawer}}</h1>
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