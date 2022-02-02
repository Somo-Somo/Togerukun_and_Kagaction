<template>
    <v-list class="py-0" width="100%">
      <v-col class="px-md-0" v-for="card in cards" :key="card" :cards="cards">
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
              <v-icon>mdi-dots-vertical</v-icon>
            </v-list-item>
          </v-list>
        </v-card>
      </v-col>
    </v-list>
</template>

<script>
export default {
  props: {
    cards: {
      type: Array,
    },
    tab : {
        type: String,
    }
  },
};
</script>