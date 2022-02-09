<template>
  <v-list class="py-0" width="100%">
    <v-col class="px-md-0" v-for="card in cards" :key="card" :cards="cards">
      <v-card class="rounded" outlined>
        <v-list class="py-0 d-flex align-content-center" style="height: 80px">
          <v-list-item @click="toHypothesisDetail" link>
            <v-list-item-content class="pa-0 d-flex flex-nowrap">
              <div>
                <v-list-item-subtitle class="d-flex align-content-start py-3">
                  <v-icon size="8">circle</v-icon>
                  <p
                    class="ma-0 px-2 grey--text font-weight-bold"
                    style="font-size: 12px"
                  >
                    Not yet
                  </p>
                </v-list-item-subtitle>
                <v-list-item-title class="pb-4">
                  <p class="font-weight-black ma-0">
                    {{ card }}
                  </p></v-list-item-title
                >
                <v-menu class="rounded-lg elevation-0" offset-y>
                  <template v-slot:activator="{ on, attrs }">
                    <v-list-item-action
                      class="ma-0"
                      style="
                        position: absolute;
                        top: 32px;
                        right: 16px;
                      "
                    >
                      <v-btn
                        v-bind="attrs"
                        v-on="on"
                        small
                        icon
                        link
                      >
                        <v-icon>mdi-dots-vertical</v-icon>
                      </v-btn>
                    </v-list-item-action>
                  </template>
                  <v-list>
                    <v-list-item v-for="menu in cardMenu" :key="menu" link>
                      <v-list-item-title :style="menu.color">{{ menu.title }}</v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-menu>
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
  data: () => ({
    cardMenu: [
      {title: "ゴールにする", color:""},
      {title: "今日の目標にする", color: ""},
      {title: "削除", color:"color: red"},
    ]
  }),
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
      return this.$router.push({ path: "/project/123/123" });
    },
  },
};
</script>