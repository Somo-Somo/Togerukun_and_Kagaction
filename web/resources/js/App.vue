<template>
  <v-app id="inspire">
    <Navbar />
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
import { mapGetters } from 'vuex'

export default {
  components: {
    Navbar,
    Footer,
  },
  computed: {
    ...mapGetters({
      check: 'auth/check',
    }),
    errorCode() {
      return this.$store.state.error.code;
    },
  },
  watch: {
    errorCode: {
      handler(val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push("/500");
        } else if (val === NOT_FOUND) {
          this.$router.push('/not-found');
        }
      },
      immediate: true,
    },
    $route() {
      this.$store.commit("error/setCode", null);
    },
  },
  created(){
    if(this.check){
      this.$store.dispatch("initialize/getUserHasProjectAndHypothesis", this.$route);
    }
  }
};
</script>