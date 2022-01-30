<template>
  <div class="d-flex flex-column d-md-none" style="position:fixed; width:100%; height:80px; bottom:0;">
    <!-- フッターとナビゲーション -->
    <v-bottom-navigation
      v-model="value"
      class="d-flex justify-start"
      style="background-color: #80cbc4"
      absolute
    >
      <v-bottom-sheet v-model="sheet">
        <template v-slot:activator="{ on, attrs }">
          <v-btn v-bind="attrs" v-on="on" value="recent">
            <v-icon size="24">mdi-menu</v-icon>
          </v-btn>
        </template>
        <v-list class="pa-0 rounded-t-xl" color="#00838F" absolute>
          <div
            class="d-flex justify-center rounded-t-xl"
            style="background-color: #00838f; max-height: 36px"
          >
            <v-avatar
              size="56"
              color="#00838F"
              style="position: relative; top: 0px"
            >
              <v-avatar size="40" color="indigo">
                <v-icon dark> mdi-account-circle </v-icon>
              </v-avatar>
            </v-avatar>
          </div>
          <div class="pt-2 rounded-t-xl" style="background-color: #80cbc4">
            <v-list-item
              v-for="tile in tiles"
              :key="tile.title"
              @click="sheet = false"
            >
              <v-list-item-avatar>
                <v-avatar size="32px" tile>
                  <v-icon>{{ tile.icon }}</v-icon>
                </v-avatar>
              </v-list-item-avatar>
              <v-list-item-title style="color: #424242">{{
                tile.title
              }}</v-list-item-title>
              <v-divider></v-divider>
            </v-list-item>
            <v-divider class="mx-6"></v-divider>

            <v-list-item
              class="d-flex justify-start px-0"
              style="background-color: #80cbc4"
            >
            </v-list-item>
          </div>
        </v-list>
      </v-bottom-sheet>
    </v-bottom-navigation>
  </div>
</template>

<script>
export default {
  data: () => ({
    sheet: false,
    tiles: [
      { icon: "mdi-flag-variant-outline", title: "Goal" },
      { icon: "mdi-folder-multiple-outline", title: "Project" },
      { icon: "mdi-help-circle-outline", title: "Gide" },
    ],
  }),
};
</script>