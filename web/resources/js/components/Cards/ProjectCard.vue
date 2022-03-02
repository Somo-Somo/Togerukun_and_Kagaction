<template>
<div>
  <v-list class="py-0" width="100%">
    <v-col class="px-md-0" v-for="(projectCard, index) in projectCards" :key="projectCard.name" :projectCard="projectCard">
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
                <v-list-item 
                  v-for="menu in cardMenu" 
                  :key="menu.title" 
                  @click="displayDeletingConfirmationDialog(projectCard, index)" 
                  link
                >
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
    <DeletingConfirmationDialog 
      :deletingConfirmationDialog="deletingConfirmationDialog"
      :selectedDeletingItem="selectedDeletingProject"
      @deleteItem="deleteProject"
      @cancel="cancel"
    />
</div>
</template>

<script>
import DeletingConfirmationDialog from "../Dialog/DeletingConfirmationDialog.vue";
import { mapState } from 'vuex'

export default {
  components: {
    DeletingConfirmationDialog,
  },
  data: () => ({
    cardMenu: [
      {title: "削除", color:"color: red"},
    ],
    deletingConfirmationDialog: false,
    selectedDeletingProject: {
      index: null,
      name: null,
      uuid: null,
    },
  }),
  computed: {
    ...mapState({
      projectCards : (state) => state.project.projectList
    })
  },
  methods: {
    async toHypothesis(project) {
      await this.$store.dispatch("project/selectProject", project);
      const url = "project/" + project.uuid;
      return this.$router.push({ path: url });
    },
    displayDeletingConfirmationDialog(projectCard,index) {
      this.deletingConfirmationDialog = true;
      console.info(index);
      this.selectedDeletingProject.index = index;
      this.selectedDeletingProject.name = projectCard.name;
      this.selectedDeletingProject.uuid = projectCard.uuid;
    },
    async deleteProject(){
      await this.$store.dispatch("project/deleteProject", this.selectedDeletingProject);
      this.deletingConfirmationDialog = false;
      this.selectedDeletingProject.index = null;
      this.selectedDeletingProject.name = null;
      this.selectedDeletingProject.uuid = null;
    },
    cancel(){
      this.deletingConfirmationDialog = false;
      this.selectedDeletingProject.index = null;
      this.selectedDeletingProject.name = null;
      this.selectedDeletingProject.uuid = null;
    }
  },
};
</script>