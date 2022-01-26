<template>
  <div>   
    <main>
      <div class="container"> 
        <v-app> 
        <Navbar />
        <Header />
        <RouterView />        
        </v-app>
      </div>
    </main>
  </div>
</template>

<script>
import Header from './components/Header.vue'
import Navbar from './components/Navbar.vue'

export default {
  components: {
    Header,
    Navbar,
  }
}
</script>