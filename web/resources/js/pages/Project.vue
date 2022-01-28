<template>
  <v-container
    class="d-flex flex-column justify-center my-8 px-md-16"
    style="max-width: 900px"
    fluid
  >
    <div class="d-flex justify-space-between">
      <v-subheader class="pa-md-0" style="font-size: 1rem"
        ><p class="ma-0 font-weight-bold" color="grey darken-1">
          プロジェクト一覧
        </p></v-subheader
      >
      <v-icon size="24">mdi-plus-circle</v-icon>
    </div>
    <v-col
      class="px-md-0"
      v-for="card in cards"
      :key="card"
      :card="card"
      cols="12"
    >
      <v-card class="rounded" outlined>
        <v-list style="height: 80px">
          <v-list-item style="height: 64px">
            <v-list-item-content>
              <v-list-item-title
                ><p class="font-weight-black ma-0">
                  {{ card }}
                </p></v-list-item-title
              >
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-card>
    </v-col>
    <v-col class="px-md-0" cols="12">
      <v-card class="rounded d-flex justify-space-around" outlined>
        <v-list style="height: 80px">
          <v-list-item style="height: 64px">
              <v-icon class="pa-2">mdi-plus</v-icon>
            <v-list-item-content>
              <v-list-item-title
                ><p class="grey--text font-weight-bold ma-0 pa-2">プロジェクトを追加する</p></v-list-item-title
              >
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-card>
    </v-col>
  </v-container>
</template>

<script>
export default {
  data: () => ({
    cards: ["Today", "Yesterday"],
  }),
};
</script>