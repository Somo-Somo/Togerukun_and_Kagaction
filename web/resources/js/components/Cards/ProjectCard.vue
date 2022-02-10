<template>
  <v-list class="py-0" width="100%">
    <v-col class="px-md-0" v-for="card in cards" :key="card" :card="card">
      <v-card class="rounded" outlined>
        <v-list class="py-0" style="height: 80px">
          <v-list-item @click="toHypothesis" style="height: 80px" link>
            <v-list-item-content>
              <v-list-item-title
                ><p class="font-weight-black ma-0">
                  {{ card }}
                </p></v-list-item-title
              >
            </v-list-item-content>

            <v-menu class="rounded-lg elevation-0" offset-y>
              <template v-slot:activator="{ on, attrs }">
                <v-list-item-action
                  class="ma-0"
                  style="position: absolute; top: 32px; right: 16px"
                >
                  <v-btn v-bind="attrs" v-on="on" small icon link>
                    <v-icon>mdi-dots-vertical</v-icon>
                  </v-btn>
                </v-list-item-action>
              </template>
              <v-list>
                <v-list-item v-for="menu in cardMenu" :key="menu" link>
                  <v-list-item-title :style="menu.color">{{
                    menu.title
                  }}</v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>
          </v-list-item>
        </v-list>
      </v-card>
    </v-col>
  </v-list>
</template>

<script>
export default {
  data: () => ({
    cards: ["VizHD", "開発", "マーケティング", "営業", "CS", "経理", "総務"],
    cardMenu: [
      {title: "削除", color:"color: red"},
    ]
  }),
  methods: {
    toHypothesis: function () {
      return this.$router.push({ path: "/projects/123" });
    },
  },
};
</script>