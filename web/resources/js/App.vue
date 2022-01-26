<template>
  <div>
    <main>
      <div class="container">
        <v-app>
        <Navbar />
        <RouterView />
        </v-app>
      </div>
    </main>
  </div>
</template>

<script>
import Navbar from './components/Navbar.vue'

export default {
  components: {
    Navbar,
  }
}


</script>