<template>
  <v-app-bar class="my-3 my-md-0" style="height: 72px" color="white" elevation="0" app>
    <v-app-bar-nav-icon
      @click="$emit('clickMenu')"
      class="mt-3 hidden-sm-and-down"
    ></v-app-bar-nav-icon>
    <v-toolbar-title class="mt-3 px-0 ml-md-2">
        <h2 class="d-flex justify-start">プロジェクト</h2>
    </v-toolbar-title>
  </v-app-bar>
</template> 
