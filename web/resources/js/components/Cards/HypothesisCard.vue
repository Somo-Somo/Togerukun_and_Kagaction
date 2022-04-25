<template>
<div> 
  <v-list class="py-0" width="100%">
    <v-col 
      class="px-md-0"
      v-for="hypothesis in selectHypothesisList" 
      v-model="hypothesis.showHypothesisList"
      :key="hypothesis.uuid"
      :class="cardShow(hypothesis) ? '' : 'd-none'"
      :style="$vuetify.breakpoint.smAndUp ? 'padding:12px 0px' : 'padding:8px'"
      >
      <div class="d-flex">
      <!-- 課題一覧 -->
      <div 
        v-if="hypothesisStatus.name === '課題一覧'"
        class="d-flex">
        <div :style="depth(hypothesis)"></div>
        <div v-if="hypothesis.child" class="d-flex align-content-center"> 
          <v-icon
            v-if="hypothesis.toggle"
            @click="onClickShowAndHideHypothesis(hypothesis)"
            style="background-color: none;"
            >{{ hypothesis.toggle }}
          </v-icon>
        </div>
        <div v-if="!hypothesis.child" style="width: 24px"></div>
      </div>
      <!-- 予定 -->
      <div v-if="hypothesisStatus.name === '課題一覧'" class="d-flex">
      </div>
      <v-card class="rounded" style="width: 100%;" outlined>
        <v-list 
          class="py-0 d-flex align-content-center" 
          :style="$vuetify.breakpoint.smAndUp ? 'height:80px' : 'height:64px'"
        >
          <v-list-item 
            style="width: 100%" 
            @click="toHypothesisDetail(hypothesis)" 
            link>
            <v-list-item-content class="pa-0 d-flex">
              <div style="width: 100%;">
                <v-list-item-subtitle class="d-flex align-content-start mt-3 mb-1">
                  <div class="d-flex pr-1" :style="subTitle(hypothesis).backgroundColor" v-if="subTitle(hypothesis)">
                    <v-icon :size="subTitle(hypothesis).iconSize" :color="subTitle(hypothesis).iconColor">{{ subTitle(hypothesis).icon }}</v-icon>
                    <p
                      class="ma-0 px-2 font-weight-bold align-self-center"
                      :class="subTitle(hypothesis).fontColor"
                      :style="$vuetify.breakpoint.smAndUp ? 'font-size:12px' : 'font-size:8px'"
                    >
                       {{ subTitle(hypothesis).title }}
                    </p>
                  </div>
                  <div class="d-flex" style="max-width:66%"> 
                    <p
                      class="ma-0 grey--text font-weight-bold align-self-center"
                      style="font-size: 8px; max-width:100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                    >
                      {{ parentName(hypothesis) }}
                    </p>
                    <p
                        class="ma-0 grey--text font-weight-bold align-self-center"
                        style="font-size: 8px;"
                    >
                     {{ parentType(hypothesis) }}
                    </p>
                  </div>
                </v-list-item-subtitle>
                <v-list-item-title class="py-2 pb-4">
                  <p 
                    class="font-weight-black ma-0"
                    style="max-width:calc(100% - 36px); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                    :style="$vuetify.breakpoint.smAndUp ? 'font-size:1rem' : 'font-size:0.8rem'"
                  >
                    {{ hypothesis.name }}
                  </p></v-list-item-title
                >
                <v-menu class="rounded-lg elevation-0" offset-y>
                  <template v-slot:activator="{ on, attrs }">
                    <v-list-item-action
                      class="ma-0"
                      style="position: absolute; right: 16px;"
                      :style="$vuetify.breakpoint.smAndUp ? 'top: 28px;' : 'top: 24px;'"
                    >
                      <v-btn
                        v-bind="attrs"
                        v-on="on"
                        small
                        icon
                        link
                      >
                        <v-icon :size="$vuetify.breakpoint.smAndUp ? '24' : '20'">
                          mdi-dots-vertical
                        </v-icon>
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
    <div
      class="my-4" 
      v-show="!hypothesisStatus.show && hypothesisStatus.name !== 'ゴール' && hypothesisStatus.name !== '目標'"
    >
      <p
        class="grey--text font-weight-bold ma-0 pa-md-2 px-4 py-2"
        :style="$vuetify.breakpoint.smAndUp ? 'font-size:18px;' : 'font-size:14px;'"
      >
          {{hypothesisStatus.name}}はありません
      </p>
    </div>
  </v-list>
    <DeletingConfirmationDialog 
      :deletingConfirmationDialog="deletingConfirmationDialog"
      :selectedDeletingItem="selectedDeletingHypothesis"
      :loading="false"
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
    selectedDeletingHypothesis: { name: null },
    cardMenu: [
      {title: "削除", color:"color: red"},
    ],
    subtitle: {
      accomplish : {
            icon: 'mdi-circle', 
            iconSize: 8, 
            title: '完了', 
            backgroundColor: 'background-color: null', 
            iconColor: 'green',
            fontColor : '#212121--text'
      },
      date : {
            icon: 'mdi-clock-outline',
            iconSize: 14, 
            iconColor: '#212121',
            fontColor : '#212121--text'
      }
    }
  }),
  props: {
    project : {
      type: Object,
    },
    selectHypothesis : {
      type: Object,
    },
    hypothesisList: {
      type: Array,
    },
    hypothesisStatus: {
      type: Object,
    },
  },
  computed : {
    selectHypothesisList(){
      return this.hypothesisStatus.name === "予定" ? this.sortScheduleList() : this.hypothesisList;
    },
    cardShow() {
      return function (hypothesis) {        
        if (this.hypothesisStatus.name === "ゴール") 
          return hypothesis.depth === 0 ? this.showHypothesis() : false;
        
        if (this.hypothesisStatus.name === "課題一覧") {
          if(hypothesis) this.hypothesisStatus.show = true;
          if (hypothesis.depth === 0) hypothesis.showHypothesisList = true;
          return hypothesis.showHypothesisList ? true : false;
        }

        if (this.hypothesisStatus.name === "予定") 
          return hypothesis.date ? this.showHypothesis() : false; 

        if (this.hypothesisStatus.name === "完了") 
          return hypothesis.accomplish ? this.showHypothesis() : false; 

        if (this.hypothesisStatus.name === "目標") 
          return this.selectHypothesis.uuid === hypothesis.parentUuid ? this.showHypothesis() : false;

        return false;
      }
    },
    subTitle() {
      return (hypothesis) => {
        if (hypothesis.accomplish) {
          return this.subtitle.accomplish; 
        } else if (hypothesis.date) {
          return this.calcDate(hypothesis);
        } else {
          return false;
        }
      }  
    },
    parentName() {
      return (hypothesis) => {
        if (hypothesis.depth === 0) {
           return '「' + this.project.name;
        } else if (hypothesis.depth > 0)  {
          let parentName;
          this.hypothesisList.map((value) => {
            if (hypothesis.parentUuid === value.uuid) parentName =  value.name;
          })
          return '「' + parentName ;
        }
      }
    },
    parentType() {
      return (hypothesis) => {
        if (hypothesis.depth === 0)  return '」のゴール';
        if (hypothesis.depth > 0) return '」の課題';
      }
    },
    depth() {
      return (hypothesis) => {
        return 'padding-left:'+(Number(hypothesis.depth) * 8) + 'px'; 
      }
    } 
  },
  methods: {
    showHypothesis(){
      this.hypothesisStatus.show = true;
      return true;
    },
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
      this.selectedDeletingHypothesis = { name: null };
    },
    onClickCancel(){
      this.deletingConfirmationDialog = false;
      this.selectedDeletingHypothesis = { name: null };
    },
    onClickShowAndHideHypothesis(hypothesis){
      for (const hypothesisKey in this.hypothesisList) {
        let key = Number(hypothesisKey);
       // クリックされた仮説と同じ仮説の時
       if (hypothesis.uuid === this.hypothesisList[hypothesisKey].uuid) {
         this.hypothesisList[hypothesisKey].toggle = 
          hypothesis.toggle === "mdi-menu-right" ? "mdi-menu-down" : "mdi-menu-right"; 
       }
        // クリックされた仮説の子仮説の時
       if (hypothesis.uuid === this.hypothesisList[hypothesisKey].parentUuid){  
          // onClickOpenの時   
          if (hypothesis.toggle === "mdi-menu-down") {
            this.hypothesisList[key].showHypothesisList = true;
          } 
          // onClickCloseの時
          else if (hypothesis.toggle === "mdi-menu-right") {
            this.hypothesisList[key].showHypothesisList = false;
            while (key < Object.keys(this.hypothesisList).length) {
              if(this.hypothesisList[Number(hypothesisKey)].depth > this.hypothesisList[key].depth) break;
              this.hypothesisList[key].showHypothesisList = false;
              if(this.hypothesisList[key].toggle === "mdi-menu-down") 
                this.hypothesisList[key].toggle = "mdi-menu-right";
              if(key + 1 === Object.keys(this.hypothesisList).length) break;
              key = key + 1;
            }
          } 
       }
       this.$set(this.hypothesisList, key, this.hypothesisList[key]);
       this.cardShow(this.hypothesisList[key]);
      }
      return this.hypothesisList;
    },
    calcDate (hypothesis){
        const today = (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10);
        const diff = (new Date(hypothesis.date) - new Date(today)) / (60*60*1000*24);
        if (diff > 0) {
          this.subtitle.date.title = '残り' + diff + '日';
          this.subtitle.date.backgroundColor = diff < 4 ? 'background-color: yellow' : null;
        } else if (diff === 0) {
          this.subtitle.date.title = '今日';
          this.subtitle.date.backgroundColor = 'background-color: skyblue';
        } else {
          this.subtitle.date.title = Math.abs(diff) + '日経過';
          this.subtitle.date.backgroundColor = 'background-color: coral'
        }
        return this.subtitle.date;
    },
    sortScheduleList (){
        const scheduleList = [];
        for (const [key, todo] of Object.entries(this.hypothesisList)) {
          if (todo.date) scheduleList.push(todo);
        }
        let sortScheduleList = scheduleList.sort(function(a, b) {
          return (a.date < b.date) ? -1 : 1;  //オブジェクトの昇順ソート
        });
        return sortScheduleList;
    }
  },
  watch: {
    hypothesisList (next,prev) {
      return;
    }
  }
};
</script>
<style scoped lang='sass'>
.toggleTransrateRight
  transform: rotate(0.25turn)

.v-icon.v-icon:after
  background-color: transparent !important
</style>
