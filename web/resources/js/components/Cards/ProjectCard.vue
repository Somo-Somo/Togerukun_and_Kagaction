<template>
<div>
  <v-list class="py-0" width="100%">
    <v-col 
      class="px-md-0" 
      v-for="project in projectList" 
      :key="project.name"
      :style="$vuetify.breakpoint.smAndUp ? 'padding:12px 0px' : 'padding:8px'"
    >
      <v-card class="rounded" outlined>
        <v-list class="py-0" :style="$vuetify.breakpoint.smAndUp ? 'height:80px' : 'height:64px'">
          <v-list-item 
            @click="toHypothesis(project)" 
            :style="$vuetify.breakpoint.smAndUp ? 'height:80px' : 'height:64px'"
            link
          >
            <v-list-item-content>
              <v-list-item-title
                ><p 
                 class="font-weight-black ma-0"
                 :style="$vuetify.breakpoint.smAndUp ? 'font-size:1rem' : 'font-size:0.75rem'
                ">
                  {{ project.name }}
                </p></v-list-item-title
              >
            </v-list-item-content>

            <v-menu class="rounded-lg elevation-0" offset-y>
              <template v-slot:activator="{ on, attrs }">
                <v-list-item-action class="ma-0">
                  <v-btn v-bind="attrs" v-on="on" small icon link>
                    <v-icon :size="$vuetify.breakpoint.smAndUp ? '24' : '20'">
                      mdi-dots-vertical
                    </v-icon>
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
      :loading="submitLoading"
      @deleteItem="deleteProject"
      @onClickCancel="onClickCancel"
    />
</div>
</template>

<script>
import DeletingConfirmationDialog from "../Dialog/DeletingConfirmationDialog.vue";

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
    submitLoading: false,
  }),
  props: {
    projectList: {
      type: Array,
    },
  },
  methods: {
    toHypothesis(project) {
      this.$store.dispatch("project/selectProject", project);
      const url = "project/" + project.uuid;
      return this.$router.push({ path: url });
    },
    selectMenu(menuTitle, project) {
      if (menuTitle === "編集") {
        this.$emit("onClickEdit", project);
      } else if (menuTitle === "削除") {
        this.deletingConfirmationDialog = true;
        this.selectedDeletingProject.name = project.name;
        this.selectedDeletingProject.uuid = project.uuid;
      }
    },
    async deleteProject(){
      this.submitLoading = true;
      await this.$store.dispatch("project/deleteProject", this.selectedDeletingProject);
      this.submitLoading = false;
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