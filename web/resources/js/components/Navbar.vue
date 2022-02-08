<template>
  <div class="hidden-sm-and-down" style="width: 256px">
    <v-navigation-drawer color="#80CBC4" v-model="drawer" app hide-overlay>
      <v-app-bar
        class="d-flex px-0 py-0 mt-2"
        color="#80CBC4"
        style="height: 72px;"
        elevation="0"
        absolute
      >
      <div class="d-flex justify-space-between" style="width: 222px;">
      <img :src="'/img/VizHD_logo_app.png'" alt="アプリロゴ">
      <v-icon>mdi-chevron-double-left</v-icon>
      </div>
      </v-app-bar>
      <v-list class="pb-4" style="padding-top: 72px">
        <v-list-item
          v-for="[icon, text] in links"
          :key="icon"
          class="d-flex px-8"
          style="height: 48px"
          link
        >
          <v-list-item-icon class="align-self-center mr-6">
            <v-icon color="teal lighten-5">{{ icon }}</v-icon>
          </v-list-item-icon>
          <v-list-item-content class="align-self-center">
            <v-list-item-title>{{ text }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <v-divider class="mx-6" color="#80CBC4"></v-divider>
      <v-subheader class="px-8 pt-4" style="font-size: 0.75em"
        >プロジェクト</v-subheader
      >
      <v-list class="overflow-y-auto py-0" height="calc(100% - 304px)">
        <v-list-item
          v-for="text in projects"
          :key="text"
          class="d-flex px-8"
          style="height: 48px"
          link
        >
          <v-list-item-icon class="align-self-center mr-6">
            <v-icon color="teal lighten-5">mdi-folder-outline</v-icon>
          </v-list-item-icon>
          <v-list-item-content class="align-self-center">
            <v-list-item-title>{{ text }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <v-footer color="#80CBC4" absolute>
        <v-divider color="#80CBC4"></v-divider>
        <v-sheet
          class="d-flex flex-row justify-space-around pl-2"
          style="height: 72px"
          color="#80CBC4"
        >
          <v-list-item-avatar
            color="grey darken-1"
            size="36"
          ></v-list-item-avatar>
          <v-list-item-content>
            <v-list-item-title>四戸岸 勇仁</v-list-item-title>
            <v-list-item-subtitle style="font-size: 0.75em">
              y.shitogishi@vizhd.co.jp
            </v-list-item-subtitle>
          </v-list-item-content>
        </v-sheet>
      </v-footer>
    </v-navigation-drawer>
  </div>
</template>

<script>
export default {
  props: {
    drawer: {
      type: Boolean,
    },
  },
  data: () => ({
    links: [
      // ["mdi-flag-variant-outline", "目標"],
      ["mdi-folder-multiple-outline", "プロジェクト"],
      ["mdi-help-circle-outline", "ガイド"],
    ],
    projects: [
      "VizHD",
      "開発",
      "マーケティング",
      "VizHD",
      "開発",
      "マーケティング",
      "VizHD",
      "開発",
      "マーケティング",
      "VizHD",
      "開発",
      "マーケティング",
    ],
  }),
};
</script>