<template>
  <v-list class="py-0">
    <v-col
      class="px-md-0"
      v-for="card in cards"
      :key="card"
      :card="card"
    >
      <v-card class="rounded" style="width: 80vw" outlined>
        <v-list class="py-0" style="height: 80px">
          <v-list-item style="height: 80px" link>
            <v-list-item-content>
              <v-list-item-title
                ><p class="font-weight-black ma-0">
                  {{ card }}
                </p></v-list-item-title
              >
            </v-list-item-content>
            <v-icon>mdi-dots-vertical</v-icon>
          </v-list-item>
        </v-list>
      </v-card>
    </v-col>
  </v-list>
</template>



<script>
import AddCard from "./AddCard.vue";
export default {
  components: {
    AddCard,
  },
  data: () => ({
    cards: ["VizHD", "開発", "マーケティング", "営業", "CS", "経理","総務"],
  }),
};
</script>