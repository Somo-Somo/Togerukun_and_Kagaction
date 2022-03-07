<template>
  <v-app id="inspire">
    <Navbar />
    <Header :headerTitle="headerTitle" />
    <v-main class="my-md-2">
      <RouterView />
    </v-main>
    <Footer />
  </v-app>
</template>

<script>
import Header from "./components/Header.vue";
import Navbar from "./components/Navbar.vue";
import Footer from "./components/Footer.vue";
import { INTERNAL_SERVER_ERROR } from "./util";

export default {
  components: {
    Header,
    Navbar,
    Footer,
  },
  computed: {
    headerTitle() {
      switch (this.$route.matched[0].path) {
        case "/projects":
          return "プロジェクト";
        case "/projects/:id":
          return "仮説一覧";
        case "/projects/:id/:detailId":
          return "仮説詳細";
      }
    },
    errorCode() {
      return this.$store.state.error.code;
    },
  },
  watch: {
    errorCode: {
      handler(val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push("/500");
        }
      },
      immediate: true,
    },
    $route() {
      this.$store.commit("error/setCode", null);
    },
  },
  created(){
    this.$store.dispatch("initialize/getUserHasProjectAndHypothesis", this.$route);
  }
};
</script>