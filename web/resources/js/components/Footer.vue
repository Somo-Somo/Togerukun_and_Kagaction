<template>
  <div class="d-flex flex-column d-md-none" style="position:fixed; width:100%; height:80px; bottom:0;">
    <!-- フッターとナビゲーション -->
    <v-bottom-navigation
      v-model="value"
      class="d-flex"
      style="background-color: #80cbc4"
      absolute
      grow
    >
    <v-btn 
      @click="onClickBtn(tile.url)"
      v-for="tile in tiles" 
      :key="tile.text"
    >
      <span>{{tile.text}}</span>
      <v-icon>{{tile.icon}}</v-icon>
    </v-btn>
    </v-bottom-navigation>
  </div>
</template>

<script>
export default {
  data: () => ({
    sheet: false,
    tiles: [
      {icon: "mdi-folder-multiple-outline", text: "Projects", url: "/projects"},
      {icon: "mdi-flag-outline", text: "Current Goal", url: "/current_goals"},
      {icon: "mdi-account-circle-outline", text: "User", url: "/user"},
    ],
  }),
  methods: {
    onClickBtn (url){
      return this.$router.push({ path: url });
    }
  },
};
</script>