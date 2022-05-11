<template>
  <v-container
    class="d-flex flex-column py-0 my-md-2 px-md-16"
    :style="$vuetify.breakpoint.mdAndUp ? 'max-width: 900px' : ''"
    fluid
  >
     <Header 
      :project="project"
      :todo="todo"
      :parentTodo="parentTodo"
      />
      <template>
        <div
          class="d-flex flex-column"
          :class="
            $vuetify.breakpoint.mdAndUp
              ? 'todoDetailMain'
              : 'spTodoDetailMain'
          "
        >
          <div class="py-2 py-md-4 d-flex justify-start flex-column">
            <v-subheader
              class="pa-md-0 d-flex"
              :class="
                $vuetify.breakpoint.mdAndUp
                  ? 'todoSubTitle'
                  : 'spTodoSubTitle'
              "
            >
              <p class="ma-0 font-weight-bold" color="grey darken-1">{{ subHeader }}</p>
            </v-subheader>
            <v-textarea
              label="名前を入力"
              v-model="todo.name"
              @change="editTodoName"
              class="pa-0 "
              rows="1"
              :class="$vuetify.breakpoint.smAndUp ? 'text-h5' : 'text-h6'"
              auto-grow
              single-line
              solo
              flat
              hide-details
            ></v-textarea>
          </div>
          <div class="d-flex px-1">
            <div
              class="py-2 d-flex justify-start"
              :style="
                $vuetify.breakpoint.mdAndUp ? 'height: 72px' : 'height: 48px'
              "
            >
              <v-subheader
                class="d-flex align-self-center pa-md-0"
                :class="
                  $vuetify.breakpoint.mdAndUp
                    ? 'todoSubTitle'
                    : 'spTodoSubTitle'
                "
              >
                <p class="ma-0 font-weight-bold" style="min-width:36px;" color="grey darken-1">達成：</p>
              </v-subheader>
              <v-col class="px-4 py-0 d-flex align-self-center">
                <v-checkbox
                  v-model="todo.accomplish"
                  @click="onClickAccomplish(todo.accomplish)"
                ></v-checkbox>
              </v-col>
            </div>
            <div
              class="py-2 ml-md-2 d-flex align-self-center"
              :style="
                $vuetify.breakpoint.mdAndUp ? 'height: 72px' : 'height: 48px'
              "
            >
              <v-subheader class="d-flex align-self-center pa-md-0">
                <p class="ma-0 font-weight-bold" color="grey darken-1">日付 :</p>
              </v-subheader>
              <v-col class="px-md-4 pa-0 d-flex align-self-center">
                <Calender :project="project"/>
              </v-col>
            </div>
          </div>
          <div class="">
            <div class="d-flex justify-space-between flex-column">    
              <v-tabs 
                v-model="tab" 
                class="px-0" 
                color="black" 
                :height="$vuetify.breakpoint.mdAndUp ? '' : '36'"
              >
                <v-tabs-slider color="#80CBC4"></v-tabs-slider>
                <v-tab
                  class="px-0"
                  v-for="kind in linkedToDo"
                  :key="kind.name"
                  :class="$vuetify.breakpoint.mdAndUp ? '' : 'spTabStyle'"
                >
                  <p class="ma-0 font-weight-bold">{{ kind.name }}</p>
                </v-tab>
              </v-tabs>
              <v-subheader
                v-if="tab === 0"
                class="px-md-0 mt-2 todoSubTitle"
                v-show="$vuetify.breakpoint.smAndUp"
              >
                <p 
                  class="ma-0 font-weight-black caption align-self-center" 
                  color="grey lighten-1"
                  style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                  :style="$vuetify.breakpoint.smAndUp ? 'max-width: 70%' : 'max-width: 40vw'"
                >
                  「{{todo.name}}
                </p>
                <p class="ma-0 font-weight-black caption align-self-center" color="grey lighten-1">
                  {{ assistSubHeaderText(tab) }}
                </p>
              </v-subheader>
            </div>
            <div
              class="overflow-y-auto d-flex flex-column"
              :class="$vuetify.breakpoint.mdAndUp ? 'cardStyle' : 'spCardStyle'"
            >
              <TodoCards 
                v-if="tab === 0"
               :project="project" 
               :selectTodo="todo" 
               :todoList="todoList" 
               :todoStatus="linkedToDo[0]" />
               <Comments 
                v-if="tab === 1 && todo.comments.length > 0"
                :todo="todo"
               />
              <!-- PC版追加カード -->
              <NewAdditionalCard 
               @clickAditional="onClickCreate" 
               :category="linkedToDo[tab].name"/>
            </div>
          </div>
        </div>
        <!-- スマホ版追加ボタン -->
        <!-- <SpBottomBtn @clickAditional="onClickCreate" :headerTitle="page" /> -->
      </template>
      <form class="form" @submit.prevent="submitForm()">
        <InputForm
          v-if="form"
          @onClickCancel="onClickCancel"
          @submitForm="submitForm"
          :category="additionalInputFormLabel"
          :inputForm="inputForm"
          :loading="submitLoading" 
        />
      </form>
  </v-container>
</template>

<script>
import Header from "../components/Header.vue";
import Calender from "../components/Calender.vue";
import TodoCards from "../components/Cards/TodoCard.vue";
import Comments from "../components/Comments.vue";
import NewAdditionalCard from "../components/Cards/NewAddtionalCard.vue";
import SpBottomBtn from "../components/Buttons/SpBottomBtn.vue";
import InputForm from "../components/InputForm.vue";
import { mapGetters, mapState } from 'vuex';

export default {
  components: {
    Header,
    Calender,
    TodoCards,
    Comments,
    NewAdditionalCard,
    SpBottomBtn,
    InputForm,
  },
  data: () => ({
    tab: 0,
    linkedToDo: [
      {name: "ToDo", show: false},
      {name: "コメント", show: false}
    ],
    submitLoading: false,
    form: false,
    date: null,
  }),
  computed : {
    ...mapState({
      apiStatus: (state) => state.auth.apiStatus,
    }),
   ...mapGetters({
      user: 'auth/user',
      inputFormName: 'form/name',
      inputForm: 'form/inputForm',
      project: 'project/project',
      todo: 'todo/todo',
      parentTodo: 'todo/parentTodo',
      todoList: 'todo/todoList',
    }),
    subHeader() {
      return this.todo.depth === 0 ? 'ゴール' : '｢'+ this.parentTodo.name +'｣ のためのToDo';
    },
    additionalInputFormLabel(){
      if (this.tab === 0) {
        return '「' +this.todo.name + '」のためのToDo';
      } else {
        return 'コメント';
      }
    },
    assistSubHeaderText(){
      return (tab) => {
        if (tab === 0) {
          return '」を達成するには？'; 
        }
      }  
    },
  },
  methods: {
    onClickAccomplish (accomplish){
      this.$store.dispatch(
        "todo/updateAccomplish",
         { accomplish:accomplish, todoUuid:this.todo.uuid }
      );
    },
    onClickCreate () {
      this.$store.dispatch("form/onClickCreate");
      this.form = true;
    },    
    onClickCancel() {
      this.$store.dispatch("form/closeForm");
      this.form = false;
    },
    submitForm(){
      this.$store.dispatch("form/closeForm");
      if (this.inputFormName && this.tab === 0) {
        this.$store.dispatch(
          "todo/createTodo", 
          {parent: this.todo, name: this.inputFormName}
        );
      } else if (this.inputFormName && this.tab === 1) {
        this.$store.dispatch(
          "todo/createComment", 
          {todo: this.todo, text: this.inputFormName, user: this.user}
        );
      }
      this.form = false;
    },
    editTodoName(){
      if (this.todo.name) {
        this.$store.dispatch("todo/editTodo", this.todo);
      }
    },
  },
  watch: {
    $route (to, from) {
      //ブラウザバックで戻った先が再び仮説詳細ページだった場合
      if (to.params.id === this.parentTodo.uuid) {
        this.$store.dispatch("todo/selectTodo", this.parentTodo);
      }
    },
    inputForm(inputForm) {
      if (!inputForm) {
        this.form = false;
      }
    },
  },
};
</script>

<style scoped lang='sass'>
.todoDetailMain
  width: 772px
  position: fixed

.spTodoDetailMain
  width: calc(100vw - 24px)
  position: fixed
  top: 72px

.todoSubTitle
  font-size: 1rem
  height: 36px

.spTodoSubTitle
  font-size: 12px
  height: 24px
  padding: 0 0 0 12px

.spTabStyle
  width: 25%
  height: 36px
  font-size: 0.75rem

.cardStyle
  height: calc(100vh - 376px)
  position: relative

.spCardStyle
  height: calc(100vh - 320px)
  position: relative
</style>