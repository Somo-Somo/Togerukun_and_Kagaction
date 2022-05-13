<template>
<div> 
  <v-list class="py-0" width="100%">
    <v-col 
      class="px-md-0"
      v-for="todo in selectTodoList" 
      v-model="todo.showTodoList"
      :key="todo.uuid"
      :class="cardShow(todo) ? '' : 'd-none'"
      :style="$vuetify.breakpoint.smAndUp ? 'padding:8px 0px' : 'padding:8px'"
      >
      <div class="d-flex">
      <!-- ToDo一覧 -->
      <div 
        v-if="todoStatus.name === 'ToDo一覧'"
        class="d-flex">
        <div :style="depth(todo)"></div>
        <div v-if="todo.child" class="d-flex align-content-center"> 
          <v-icon
            v-if="todo.toggle"
            @click="onClickShowAndHideTodo(todo)"
            style="background-color: none;"
            >{{ todo.toggle }}
          </v-icon>
        </div>
        <div v-if="!todo.child" style="width: 24px"></div>
      </div>
      <!-- 予定 -->
      <div v-if="todoStatus.name === 'ToDo一覧'" class="d-flex">
      </div>
      <v-card class="rounded" style="width: 100%;" outlined>
        <v-list 
          class="py-0 d-flex align-content-center" 
          :style="$vuetify.breakpoint.smAndUp ? 'height:80px' : 'height:64px'"
        >
          <v-list-item 
            style="width: 100%" 
            @click="toTodoDetail(todo)" 
            link>
            <v-list-item-content class="pa-0 d-flex">
              <div style="width: 100%;">
                <v-list-item-subtitle class="d-flex align-content-start mt-3 mb-1">
                  <div class="d-flex pr-1" :style="subTitle(todo).backgroundColor" v-if="subTitle(todo)">
                    <v-icon :size="subTitle(todo).iconSize" :color="subTitle(todo).iconColor">{{ subTitle(todo).icon }}</v-icon>
                    <p
                      class="ma-0 px-2 font-weight-bold align-self-center"
                      :class="subTitle(todo).fontColor"
                      :style="$vuetify.breakpoint.smAndUp ? 'font-size:12px' : 'font-size:8px'"
                    >
                       {{ subTitle(todo).title }}
                    </p>
                  </div>
                  <div class="d-flex" style="max-width:66%"> 
                    <p
                      class="ma-0 grey--text font-weight-bold align-self-center"
                      style="font-size: 8px; max-width:100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                    >
                      {{ parentName(todo) }}
                    </p>
                    <p
                        class="ma-0 grey--text font-weight-bold align-self-center"
                        style="font-size: 8px;"
                    >
                     {{ parentType(todo) }}
                    </p>
                  </div>
                </v-list-item-subtitle>
                <v-list-item-title class="py-2 pb-4">
                  <p 
                    class="font-weight-black ma-0"
                    style="max-width:calc(100% - 36px); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                    :style="$vuetify.breakpoint.smAndUp ? 'font-size:1rem' : 'font-size:0.8rem'"
                  >
                    {{ todo.name }}
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
                    <v-list-item v-for="menu in cardMenu" :key="menu.title" @click="selectMenu(menu.title, todo)" link>
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
      v-show="!todoStatus.show && todoStatus.name !== 'ゴール' && todoStatus.name !== 'ToDo'"
    >
      <p
        class="grey--text font-weight-bold ma-0 px-4 py-2"
        :style="$vuetify.breakpoint.smAndUp ? 'font-size:16px;' : 'font-size:14px;'"
      >
          {{todoStatus.name}}はありません
      </p>
    </div>
  </v-list>
    <DeletingConfirmationDialog 
      :deletingConfirmationDialog="deletingConfirmationDialog"
      :selectedDeletingItemName="selectedDeletingTodo.name"
      :loading="false"
      @deleteItem="deleteTodo"
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
    selectedDeletingTodo: { name: null },
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
    selectTodo : {
      type: Object,
    },
    todoList: {
      type: Array,
    },
    todoStatus: {
      type: Object,
    },
  },
  computed : {
    selectTodoList(){
      return this.todoStatus.name === "予定" ? this.sortScheduleList() : this.todoList;
    },
    cardShow() {
      return function (todo) {        
        if (this.todoStatus.name === "予定") {
          const today = (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10);
          const diff = (new Date(todo.date) - new Date(today)) / (60*60*1000*24);
          return todo.date && !(todo.accomplish && diff < 0) ? this.showTodo() : false; 
        }

        if (this.todoStatus.name === "ToDo") 
          return this.selectTodo.uuid === todo.parentUuid ? this.showTodo() : false;

        return false;
      }
    },
    subTitle() {
      return (todo) => {
        if (todo.accomplish) {
          return this.subtitle.accomplish; 
        } else if (todo.date) {
          return this.calcDate(todo);
        } else {
          return false;
        }
      }  
    },
    parentName() {
      return (todo) => {
        if (todo.depth === 0) {
           return '「' + this.project.name;
        } else if (todo.depth > 0)  {
          let parentName;
          this.todoList.map((value) => {
            if (todo.parentUuid === value.uuid) parentName =  value.name;
          })
          return '「' + parentName ;
        }
      }
    },
    parentType() {
      return (todo) => {
        if (todo.depth === 0)  return '」のゴール';
        if (todo.depth > 0) return '」のためのToDo';
      }
    },
  },
  methods: {
    showTodo(){
      this.todoStatus.show = true;
      return true;
    },
    async toTodoDetail (todo) {
      await this.$store.dispatch("todo/selectTodo", todo);
      return this.$router.push({ path: "/todo/" + todo.uuid });
    },
    selectMenu(menuTitle, todo){
      if (menuTitle === "削除") {
        this.deletingConfirmationDialog = true;
        this.selectedDeletingTodo = todo;
      }
    },
    async deleteTodo(){
      this.deletingConfirmationDialog = false;
      await this.$store.dispatch("todo/deleteTodo", this.selectedDeletingTodo);
      this.selectedDeletingTodo = { name: null };
    },
    onClickCancel(){
      this.deletingConfirmationDialog = false;
      this.selectedDeletingTodo = { name: null };
    },
    calcDate (todo){
        const today = (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10);
        const diff = (new Date(todo.date) - new Date(today)) / (60*60*1000*24);
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
        for (const [key, todo] of Object.entries(this.todoList)) {
          if (todo.date) scheduleList.push(todo);
        }
        let sortScheduleList = scheduleList.sort(function(a, b) {
          return (a.date < b.date) ? -1 : 1;  //オブジェクトの昇順ソート
        });
        return sortScheduleList;
    }
  },
  watch: {
    todoList (next,prev) {
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
