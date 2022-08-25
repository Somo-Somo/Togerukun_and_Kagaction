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
                                :class="
                                    $vuetify.breakpoint.mdAndUp
                                        ? ''
                                        : 'spTabStyle'
                                "
                            >
                                <p class="ma-0 font-weight-bold">
                                    {{ kind.name }}
                                </p>
                            </v-tab>
                        </v-tabs>
                        <v-subheader
                            v-if="tab !== 2"
                            class="px-md-0 mt-2 todoSubTitle"
                            v-show="$vuetify.breakpoint.smAndUp"
                        >
                            <p
                                class="ma-0 font-weight-black caption align-self-center"
                                color="grey lighten-1"
                                style="
                                    white-space: nowrap;
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                "
                                :style="
                                    $vuetify.breakpoint.smAndUp
                                        ? 'max-width: 70%'
                                        : 'max-width: 40vw'
                                "
                            >
                                「{{ todo.name }}
                            </p>
                            <p
                                class="ma-0 font-weight-black caption align-self-center"
                                color="grey lighten-1"
                            >
                                {{ assistSubHeaderText(tab) }}
                            </p>
                        </v-subheader>
                    </div>
                    <div
                        class="overflow-y-auto d-flex flex-column"
                        :class="
                            $vuetify.breakpoint.mdAndUp
                                ? 'cardStyle'
                                : 'spCardStyle'
                        "
                    >
                        <TodoCards
                            v-if="tab === 0"
                            :project="project"
                            :selectTodo="todo"
                            :todoList="todoList"
                            :todoStatus="linkedToDo[0]"
                        />
                        <Cause v-if="tab === 1" :todo="todo" />
                        <Comments
                            v-if="tab === 2 && todo.comments.length > 0"
                            :todo="todo"
                        />
                        <!-- PC版追加カード -->
                        <NewAdditionalCard
                            @clickAditional="onClickCreate"
                            :category="linkedToDo[tab].name"
                        />
                    </div>
                </div>
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
import TodoCards from "../components/Cards/TodoCard.vue";
import Comments from "../components/Comments.vue";
import Cause from "../components/Cause.vue";
import NewAdditionalCard from "../components/Cards/NewAddtionalCard.vue";
import SpBottomBtn from "../components/Buttons/SpBottomBtn.vue";
import InputForm from "../components/InputForm.vue";
import { mapGetters, mapState } from "vuex";

export default {
    components: {
        TodoHeader,
        TodoState,
        TodoCards,
        Comments,
        Cause,
        NewAdditionalCard,
        SpBottomBtn,
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
