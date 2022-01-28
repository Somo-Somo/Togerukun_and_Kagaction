<template>
  <v-app-bar class="hidden-sm-and-down" style="height: 72px" color="white" elevation="0" app>
    <v-app-bar-nav-icon
      @click="$emit('clickMenu')"
      class="mt-3"
    ></v-app-bar-nav-icon>
    <v-toolbar-title class="mt-3" >
        <h2>プロジェクト</h2>
    </v-toolbar-title>
  </v-app-bar>
</template> 
