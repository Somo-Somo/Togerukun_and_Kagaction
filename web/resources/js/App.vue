<template>
  <div>   
    <main>
      <v-app> 
        <div class="d-inline-flex flex-row">
            <Navbar />
          <div class="d-flex flex-column">
            <Header />
            <RouterView />
          </div>        
        </div>
      </v-app>
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