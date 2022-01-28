<template>
  <v-app id="inspire">
    <Navbar :drawer="drawer" />
    <Header @clickMenu="isDrawer"/>
    <v-main class="my-md-2">
      <RouterView />
    </v-main>
  </v-app>
</template>

<script>
import Header from './components/Header.vue'
import Navbar from './components/Navbar.vue'

export default {
  components: {
    Header,
    Navbar,
  },
  data: () => ({ drawer: true }),
  methods: {
    isDrawer: function(){
        this.drawer = !this.drawer 
    }
  }
}
</script>