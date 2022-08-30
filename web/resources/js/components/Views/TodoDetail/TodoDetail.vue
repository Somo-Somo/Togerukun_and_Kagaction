<template>
    <v-container
        class="d-flex flex-column py-0 my-md-2 px-md-16"
        :style="$vuetify.breakpoint.mdAndUp ? 'max-width: 900px' : ''"
        fluid
    >
        <todo-header
            :project="project"
            :todo="todo"
            :parentTodo="parentTodo"
        ></todo-header>
        <template>
            <div
                class="d-flex flex-column"
                :class="
                    $vuetify.breakpoint.mdAndUp
                        ? 'todoDetailMain'
                        : 'spTodoDetailMain'
                "
            >
                <todo-state :todo="todo" :parentTodo="parentTodo"></todo-state>
                <info-associate-todo
                    :todo="todo"
                    :parentTodo="parentTodo"
                    :todoList="todoList"
                    :project="project"
                ></info-associate-todo>
            </div>
        </template>
        <form class="form" @submit.prevent="submitForm()">
            <InputForm
                v-if="form"
                @onClickCancel="onClickCancel"
                @submitForm="submitForm"
                :formCategory="formCategory"
                :formLabel="formLabel"
                :inputForm="inputForm"
                :loading="submitLoading"
            />
        </form>
    </v-container>
</template>

<script>
import TodoHeader from "../../Header.vue";
import TodoState from "./Templates/TodoState.vue";
import InfoAssociateTodo from "./Templates/InfoAssociateTodo.vue";
import InputForm from "../components/InputForm.vue";
import { mapGetters, mapState } from "vuex";

export default {
    components: {
        TodoHeader,
        TodoState,
        InfoAssociateTodo,
        InputForm,
    },
    data: () => ({
        tab: 0,
        linkedToDo: [
            { name: "ToDo", show: false },
            { name: "原因", show: false },
            { name: "コメント", show: false },
        ],
        submitLoading: false,
        form: false,
        date: null,
    }),
    computed: {
        ...mapState({
            apiStatus: (state) => state.auth.apiStatus,
        }),
        ...mapGetters({
            user: "auth/user",
            inputFormName: "form/name",
            inputForm: "form/inputForm",
            project: "project/project",
            todo: "todo/todo",
            parentTodo: "todo/parentTodo",
            todoList: "todo/todoList",
        }),
        subHeader() {
            return this.todo.depth === 0
                ? "ゴール"
                : "｢" + this.parentTodo.name + "｣ のためのToDo";
        },
        formLabel() {
            if (this.tab === 0) {
                return "「" + this.todo.name + "」のためのToDo";
            } else if (this.tab === 1) {
                return "「" + this.todo.name + "」が達成できない原因";
            } else {
                return "コメント";
            }
        },
        formCategory() {
            return this.linkedToDo[this.tab].name;
        },
        assistSubHeaderText() {
            return (tab) => {
                if (tab === 0) {
                    return "」を達成するには？";
                } else if (tab === 1) {
                    return "」が達成できない原因は？";
                }
            };
        },
    },
    methods: {
        onClickCreate() {
            this.$store.dispatch("form/onClickCreate");
            this.form = true;
        },
        onClickCancel() {
            this.$store.dispatch("form/closeForm");
            this.form = false;
        },
        submitForm(form) {
            this.$store.dispatch("form/closeForm");
            if (form.text) {
                if (this.tab === 0) {
                    this.$store.dispatch("todo/createTodo", {
                        parent: this.todo,
                        name: form.text,
                        date: form.date,
                    });
                } else if (this.tab === 1) {
                    this.$store.dispatch("todo/createCause", {
                        todo: this.todo,
                        text: form.text,
                        user: this.user,
                    });
                } else if (this.tab === 2) {
                    this.$store.dispatch("todo/createComment", {
                        todo: this.todo,
                        text: form.text,
                        user: this.user,
                    });
                }
            }
            this.form = false;
        },
    },
    watch: {
        $route(to, from) {
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

<style scoped lang="sass">
.todoDetailMain
  width: 772px
  position: fixed

.spTodoDetailMain
  width: calc(100vw - 24px)
  position: fixed
  top: 72px
</style>
