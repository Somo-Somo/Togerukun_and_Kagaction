<template>
    <v-bottom-navigation
      v-model="value"
      class="d-flex justify-start"
      style="background-color: #80cbc4"
    >
    <v-bottom-sheet v-model="sheet">
      <template v-slot:activator="{ on, attrs }">
        <v-btn v-bind="attrs" v-on="on" value="recent">
          <v-icon size="24">mdi-menu</v-icon>
        </v-btn>
      </template>
    <v-list class="pa-0" color="#80cbc4">
      <v-subheader>Open in</v-subheader>
      <v-list-item
        v-for="tile in tiles"
        :key="tile.title"
        @click="sheet = false"
      >
        <v-list-item-avatar>
          <v-avatar size="32px" tile>
            <img
              :src="`https://cdn.vuetifyjs.com/images/bottom-sheets/${tile.img}`"
              :alt="tile.title"
            />
          </v-avatar>
        </v-list-item-avatar>
        <v-list-item-title>{{ tile.title }}</v-list-item-title>
      </v-list-item>
      
      <v-list-item class="d-flex justify-start px-0" style="background-color: #80cbc4">
        <v-btn width="80" height="56" elevation="0" style="background-color: #80cbc4">
          <v-icon size="24">mdi-close</v-icon>
        </v-btn>
      </v-list-item>
    </v-list>

  </v-bottom-sheet>
  </v-bottom-navigation>
</template>

<script>
  export default {
    data: () => ({
      sheet: false,
      tiles: [
        { img: 'keep.png', title: 'Keep' },
        { img: 'inbox.png', title: 'Inbox' },
        { img: 'hangouts.png', title: 'Hangouts' },
        { img: 'messenger.png', title: 'Messenger' },
        { img: 'google.png', title: 'Google+' },
      ],
    }),
  }
</script>