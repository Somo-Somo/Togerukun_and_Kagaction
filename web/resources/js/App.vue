<template>
  <v-app id="inspire">
    <Navbar v-if="checkPath"/>
    <v-main 
      class="my-md-2" 
      :class="$vuetify.breakpoint.mdAndUp ? '' : 'py-0'">
      <RouterView />
    </v-main>
    <Footer v-if="checkPath" />
  </v-app>
</template>

<script>
import Navbar from "./components/Navbar.vue";
import Footer from "./components/Footer.vue";
import { NOT_FOUND, INTERNAL_SERVER_ERROR, UNAUTHORIZED, } from "./util";
import { mapGetters } from 'vuex'

export default {
  components: {
    Navbar,
    Footer,
  },
  computed: {
    ...mapGetters({
      check: 'auth/check',
      user: 'auth/user',
      apiStatus: 'auth/apiStatus',
      errorCode: 'error/code',
    }),
    checkPath(){
      return this.$route.path !== '/login' ? true : false;
    }
  },
  watch: {
    errorCode(val, old) {
        if (val === UNAUTHORIZED) {
           this.$router.push("/login");
        }
        else if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push("/500");
        } else if (val === NOT_FOUND) {
          this.$router.push('/not-found');
        }
    },
    $route(to, from) {
      this.$store.commit("error/setCode", null);
    },
  },
  created(){
    if(this.check){
      this.$store.dispatch("initialize/getUserHasProjectAndHypothesis", this.$route);
    } else {
       this.$router.push('/login');
    }
  }
};
</script>