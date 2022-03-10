<template>
<div> 
  <v-list class="py-0" width="100%">
    <v-col 
      class="px-md-0"
      v-for="hypothesis in hypothesisList" 
      :key="hypothesis.uuid" 
      :class="cardShow(hypothesis) ? '': 'd-none'"
      >
      <v-card class="rounded" outlined>
        <v-list class="py-0 d-flex align-content-center" style="height: 80px">
          <v-list-item @click="toHypothesisDetail(hypothesis)" link>
            <v-list-item-content class="pa-0 d-flex flex-nowrap">
              <div>
                <v-list-item-subtitle class="d-flex align-content-start mt-3 mb-1">
                  <div class="d-flex pr-1" v-if="status(hypothesis)">
                    <v-icon size="8" :color="status(hypothesis).color">circle</v-icon>
                    <p
                      class="ma-0 px-2 #212121--text font-weight-bold align-self-center"
                      style="font-size: 12px"
                    >
                       {{ status(hypothesis).title }}
                    </p>
                  </div>
                  <div class="d-flex"> 
                      <p
                        class="ma-0 grey--text font-weight-bold align-self-center"
                        style="font-size: 8px"
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
    selectedDeletingHypothesis: {
      name : null,
      uuid : null,
    },
    cardMenu: [
      {title: "ゴールにする", color:""},
      {title: "今日の目標にする", color: ""},
      {title: "削除", color:"color: red"},
    ],
  }),
  props: {
    project : {
      type: Object,
    },
    selectHypothesis : {
      type: Object,
    },
    hypothesisList: {
      type: Object,
    },
    view: {
      type: String,
    },
  },
  computed : {
    cardShow() {
      return function (hypothesis) {
        if (this.view === "ゴール") {
          return hypothesis.depth === 0 ? true : false ;
        } else if (this.view === "今日の目標") {
          return hypothesis.todaysGoal ? true : false;
        } else if (this.view === "仮説") {
          return hypothesis.depth !== 0 ? true : false ;
        } else if (this.view === "完了") {
          return hypothesis.status ? true : false; 
        } else if (this.view === "仮説詳細") {
          return this.selectHypothesis.uuid === hypothesis.parentUuid ? true : false;
        } else {
          return false;
        }
      }
    },
    status() {
      return (hypothesis) => {
        if (hypothesis.todaysGoal) {
          return {title: '今日の目標', color:'blue'};
        } else if (hypothesis.limit) {
          return {title: hypothesis.limit, color: 'purple'};
        } else if (hypothesis.status) {
          if(hypothesis.status === 'success') return {title: '成功', color: 'green'}; 
          if(hypothesis.status === 'failure') return {title: '失敗', color: 'red'}; 
        } else {
          return false;
        }
      }  
    },
    parent() {
      return (hypothesis) => {
        if (hypothesis.depth === 0) {
           return '「' + this.project.name + '」のゴール';
        } else if (hypothesis.depth > 0)  {
          let parentName;
          this.hypothesisList.map((value) => {
            if (hypothesis.parentUuid === value.uuid) parentName =  value.name;
          })
          return '「' + parentName + '」の仮説';
        }
      }
    }, 
  },
  methods: {
    async toHypothesisDetail (hypothesis) {
      await this.$store.dispatch("hypothesis/selectHypothesis", hypothesis);
      return this.$router.push({ path: "/hypothesis/" + hypothesis.uuid });
    },
    selectMenu(menuTitle, hypothesis){
      if (menuTitle === "ゴールにする") {
        
      } else if (menuTitle === "今日の目標にする") {
        
      } else if (menuTitle === "削除") {
        this.deletingConfirmationDialog = true;
        this.selectedDeletingHypothesis.name = hypothesis.name;
        this.selectedDeletingHypothesis.uuid = hypothesis.uuid;
      }
    },
    async deleteHypothesis(){
      await this.$store.dispatch("hypothesis/deleteHypothesis", this.selectedDeletingHypothesis);
      this.deletingConfirmationDialog = false;
      this.selectedDeletingHypothesis.name = null;
      this.selectedDeletingHypothesis.uuid = null;
    },
    onClickCancel(){
      this.deletingConfirmationDialog = false;
      this.selectedDeletingHypothesis.name = null;
      this.selectedDeletingHypothesis.uuid = null;
    }    
  },
};
</script>
