<template>
  <div class="hidden-sm-and-down" style="width: 256px">
    <v-navigation-drawer color="teal lighten-3" v-model="drawer" app>
      <v-sheet class="pa-4" color="teal lighten-3">
        <v-avatar class="mb-4" color="grey darken-1" size="64"></v-avatar>
        <div>yujin@vizhd.com</div>
      </v-sheet>
      <v-list>
        <v-list-item v-for="[icon, text] in links" :key="icon" link>
          <v-list-item-icon>
            <v-icon>{{ icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ text }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
  </div>
</template>

<script>
export default {
  props: {
    drawer: {
        type: Boolean
    }
  },
  data: () => ({
    links: [
      ["mdi-inbox-arrow-down", "Inbox"],
      ["mdi-send", "Send"],
      ["mdi-delete", "Trash"],
      ["mdi-alert-octagon", "Spam"],
    ],
  }),
};
</script>