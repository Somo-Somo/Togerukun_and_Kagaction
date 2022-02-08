<template>
  <v-list class="py-0" width="100%">
    <v-col class="px-md-0" v-for="card in cards" :key="card" :cards="cards">
      <v-card class="rounded" outlined>
        <v-list class="py-0" style="height: 80px">
          <v-list-item style="height: 80px" @click="toHypothesisDetail" link>
            <v-list-item-content class="pa-0">
              <v-list-item-subtitle class="d-flex justify-start pt-3 pb-1">
                <v-icon size="8">circle</v-icon>
                <p class="ma-0 px-2 grey--text font-weight-bold" style="font-size: 12px">Not yet</p>
              </v-list-item-subtitle>
              <div class="d-flex justify-space-between pb-2" style="height: 40px">
                <v-list-item-title>
                  <p class="font-weight-black ma-0">
                    {{ card }}
                  </p></v-list-item-title
                >
                <v-list-item-action>
                  <v-icon>mdi-dots-vertical</v-icon>
                </v-list-item-action>
              </div>
            </v-list-item-content>
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
    tab: {
      type: String,
    },
  },
  methods: {
    toHypothesisDetail: function () {
      return this.$router.push({ path: '/project/123/123' }) 
    },
  },
};
</script>