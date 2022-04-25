<template>
  <div class="hidden-sm-and-down" style="width: 256px">
    <v-navigation-drawer color="#80CBC4" v-model="navigation" app hide-overlay>
      <v-app-bar
        class="d-flex px-0 py-0 mt-2"
        color="#80CBC4"
        style="height: 72px"
        elevation="0"
        absolute
      >
        <v-hover v-slot="{ hover }">
          <div class="d-flex justify-space-between" style="width: 222px">
            <img :src="'/img/Kagaction_logo_app.png'" alt="アプリロゴ" />
            <v-icon
              class="my-6"
              style="height: 24px"
              :class="{ 'show-btn': hover }"
              :color="transparent"
              @click="clickChevronDoubleLeft"
              >mdi-chevron-double-left</v-icon
            >
          </div>
        </v-hover>
      </v-app-bar>
      <v-list class="pb-4" style="padding-top: 72px">
        <v-list-item
          v-for="item in items"
          :key="item.icon"
          @click="fromItem(item)"
          class="d-flex px-8"
          style="height: 48px"
          link
        >
          <v-list-item-icon class="align-self-center mr-6">
            <v-icon color="teal lighten-5">{{ item.icon }}</v-icon>
          </v-list-item-icon>
          <v-list-item-content class="align-self-center">
            <v-list-item-title>{{ item.text }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <v-divider class="mx-6" color="#80CBC4"></v-divider>
      <ProjectList />
      <v-footer color="#80CBC4" absolute>
        <v-divider color="#80CBC4"></v-divider>
        <v-sheet
          class="d-flex flex-row justify-space-around"
          style="height: 72px"
          color="#80CBC4"
        >
          <v-list-item-avatar
            color="brown"
            size="36"
          >
           <span class="white--text text-subtitle-2">{{ initial }}</span>
          </v-list-item-avatar>
          <v-list-item-content>
            <v-list-item-title>{{ user.name }}</v-list-item-title>
            <v-list-item-subtitle style="font-size: 0.75em">
              ID: {{ userId }}
            </v-list-item-subtitle>
          </v-list-item-content>
        </v-sheet>
      </v-footer>
    </v-navigation-drawer>
  </div>
</template>

<script>
import ProjectList from "../components/ProjectList.vue";
import { mapGetters } from "vuex";

export default {
    components: {
        ProjectList,
    },
  data: () => ({
    items: [
      {icon: "mdi-calendar-text", text: "予定", url: "/schedule"},
      {icon: "mdi-account-circle-outline", text: "ユーザー", url: "/user"},
    ],
    transparent: 'rgba(128, 128, 128, 0.3)',
  }),
  computed: {
     ...mapGetters({
            loading: 'initialize/loading',
            user: "auth/user",
            navigation: "navigation/navigation",
            projectList: "project/projectList",
    }),
    initial() {
      return this.user.name.charAt(0);
    },
    userId() {
      return this.user.uuid.substr(0,23);
    }
  },
  methods: {
    clickChevronDoubleLeft() {
      this.$store.dispatch("navigation/changeNavState");
    },
    async fromItem (item) {
      return this.$router.push({ path: item.url });
    },
    selectProject (project) {
      if (this.$route.params.id !== project.uuid) {
        this.$store.dispatch("project/selectProject", project);
        return this.$router.push({ path: "/project/" + project.uuid });
      }
    },
    onClickCreate () {
      this.$store.dispatch("form/onClickCreate");
    },
  },
};
</script>

<style scoped lang='sass'>
.show-btn
  color: rgba(128, 128, 128, 1) !important
</style>