<template>
  <v-app id="inspire">
    <Navbar :drawer="drawer" @clickMenu="isDrawer"/>
    <Header :drawer="drawer" :headerTitle="headerTitle" @clickMenu="isDrawer" />
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

export default {
  components: {
    Header,
    Navbar,
    Footer,
  },
  data: () => ({ drawer: true }),
  computed: {
    headerTitle() {
      switch (this.$route.matched[0].path) {
        case "/project":
          return "プロジェクト";
        case "/project/:id":
          return "仮説一覧";
        case "/project/:id/:detailId":
          return "仮説詳細";
      }
    },
  },
  methods: {
    isDrawer: function () {
      this.drawer = !this.drawer;
    },
  },
};
</script>