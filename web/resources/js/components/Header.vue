<template>
  <v-app-bar style="height: 72px" color="white" elevation="0" app>
    <v-app-bar-nav-icon
      @click="$emit('clickMenu')"
      class="mt-3"
    ></v-app-bar-nav-icon>
    <v-toolbar-title class="mt-3" >
        <h2>VizHD</h2>
    </v-toolbar-title>
  </v-app-bar>
</template> 
