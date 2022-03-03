<template>
<div>
  <v-list class="py-0" width="100%">
    <v-col class="px-md-0" v-for="(project, index) in projectCards" :key="project.name">
      <v-card class="rounded" outlined>
        <v-list class="py-0" style="height: 80px">
          <v-list-item @click="toHypothesis(project)" style="height: 80px" link>
            <v-list-item-content>
              <v-list-item-title
                ><p class="font-weight-black ma-0">
                  {{ project.name }}
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
                  @click="selectMenu(menu.title, project)" 
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
      @onClickCancel="onClickCancel"
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
      {title: "編集", color:"color: black"},
      {title: "削除", color:"color: red"},
    ],
    deletingConfirmationDialog: false,
    selectedDeletingProject: {
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
    selectMenu(menuTitle,project) {
      if (menuTitle === "編集") {
        this.$emit("onClickEdit", project);
      } else if (menuTitle === "削除") {
        this.deletingConfirmationDialog = true;
        this.selectedDeletingProject.name = project.name;
        this.selectedDeletingProject.uuid = project.uuid;
      }
    },
    async deleteProject(){
      await this.$store.dispatch("project/deleteProject", this.selectedDeletingProject);
      this.deletingConfirmationDialog = false;
      this.selectedDeletingProject.name = null;
      this.selectedDeletingProject.uuid = null;
    },
    onClickCancel(){
      this.deletingConfirmationDialog = false;
      this.selectedDeletingProject.name = null;
      this.selectedDeletingProject.uuid = null;
    }
  },
};
</script>