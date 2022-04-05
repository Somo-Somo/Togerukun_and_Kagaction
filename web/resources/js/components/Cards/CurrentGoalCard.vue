<template>
<div> 
  <v-list class="py-0" width="100%">
    <v-col 
      class="px-md-0"
      v-for="hypothesis in currentGoalList" 
      :key="hypothesis.uuid"
      >
      <div class="d-flex">
      <v-card class="rounded" style="width: 100%;" outlined>
        <v-list class="py-0 d-flex align-content-center" style="height: 80px">
          <v-list-item @click="toHypothesisDetail(hypothesis)" link>
            <v-list-item-content class="pa-0 d-flex flex-nowrap">
              <div>
                <v-list-item-subtitle class="d-flex align-content-start mt-3 mb-1">
                  <div class="d-flex pr-1">
                    <v-icon size="8" color="blue">circle</v-icon>
                    <p
                      class="ma-0 px-2 #212121--text font-weight-bold align-self-center"
                      style="font-size: 12px"
                    >
                        {{ parent(hypothesis) }}
                    </p>
                  </div>
                </v-list-item-subtitle>
                <v-list-item-title class="py-2 pb-4">
                  <p class="font-weight-black ma-0">
                    {{ hypothesis.name }}
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
                    <v-list-item v-for="menu in cardMenu" :key="menu.title" @click="selectMenu(menu.title, hypothesis)" link>
                      <v-list-item-title :style="menu.color">{{ menu.title }}</v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-menu>
              </div>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-card>
      </div>
    </v-col>
  </v-list>
    <DeletingConfirmationDialog 
      :deletingConfirmationDialog="deletingConfirmationDialog"
      :selectedDeletingItem="selectedDeletingHypothesis"
      @deleteItem="deleteHypothesis"
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
    deletingConfirmationDialog: false,
    selectedDeletingHypothesis: null,
    cardMenu: [
      {title: "削除", color:"color: red"},
    ],
  }),
  props: {
    projectList : {
      type: Object,
    },
    currentGoalList: {
      type: Array,
    },
  },
  computed : {
    parent() {
      return (hypothesis) => {
        return 'プロジェクト: 「' + this.projectList[hypothesis.projectUuid].name + '」の現在の目標';
      }
    },
  },
  methods: {
    async toHypothesisDetail (hypothesis) {
      await this.$store.dispatch("hypothesis/selectHypothesis", hypothesis);
      return this.$router.push({ path: "/hypothesis/" + hypothesis.uuid });
    },
    selectMenu(menuTitle, hypothesis){
    if (menuTitle === "削除") {
        this.deletingConfirmationDialog = true;
        this.selectedDeletingHypothesis = hypothesis;
      }
    },
    async deleteHypothesis(){
      this.deletingConfirmationDialog = false;
      await this.$store.dispatch("hypothesis/deleteHypothesis", this.selectedDeletingHypothesis);
      this.selectedDeletingHypothesis = null;
    },
    onClickCancel(){
      this.deletingConfirmationDialog = false;
      this.selectedDeletingHypothesis = null;
    },
  },
};
</script>

