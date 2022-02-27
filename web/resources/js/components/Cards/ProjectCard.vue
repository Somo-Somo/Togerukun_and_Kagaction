<template>
  <v-list class="py-0" width="100%">
    <v-col class="px-md-0" v-for="projectCard in projectCards" :key="projectCard" :projectCard="projectCard">
      <v-card class="rounded" outlined>
        <v-list class="py-0" style="height: 80px">
          <v-list-item @click="toHypothesis(projectCard)" style="height: 80px" link>
            <v-list-item-content>
              <v-list-item-title
                ><p class="font-weight-black ma-0">
                  {{ projectCard.name }}
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
import { mapState } from 'vuex'

export default {
  data: () => ({
    cards: ["VizHD", "開発", "マーケティング", "営業", "CS", "経理", "総務"],
    cardMenu: [
      {title: "削除", color:"color: red"},
    ]
  }),
  computed: {
    ...mapState({
      projectCards : (state) => state.project.projectList
    })
  },
  methods: {
    async toHypothesis(project) {
      await this.$store.dispatch("hypothesis/selectParent", project);
      const url = "project/" + project.uuid;
      return this.$router.push({ path: url });
    },
  },
};
</script>