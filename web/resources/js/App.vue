<template>
  <v-app id="inspire">
    <Navbar v-if="!errorCode" />
    <v-main class="my-md-2">
      <RouterView />
    </v-main>
    <Footer />
  </v-app>
</template>

<script>
import Navbar from "./components/Navbar.vue";
import Footer from "./components/Footer.vue";
import { NOT_FOUND, INTERNAL_SERVER_ERROR } from "./util";
import { mapGetters, mapState } from 'vuex'

export default {
  components: {
    Navbar,
    Footer,
  },
  computed: {
    ...mapState({
      check: 'auth/check',
    }),
    ...mapGetters({
      errorCode: 'error/code',
    }),
  },
  watch: {
    errorCode(val, old) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push("/500");
        } else if (val === NOT_FOUND) {
          this.$router.push('/not-found');
        }
    },
    $route() {
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