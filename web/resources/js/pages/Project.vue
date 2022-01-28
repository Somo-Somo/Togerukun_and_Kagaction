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
        <v-list class="py-0" style="height: 80px">
          <v-list-item style="height: 80px" link>
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
    <AddCard />
  </v-container>
</template>

<script>
import AddCard from "../components/AddCard.vue";
export default {
  components: {
    AddCard,
  },
  data: () => ({
    cards: ["VizHD", "開発", "マーケティング"],
  }),
};
</script>