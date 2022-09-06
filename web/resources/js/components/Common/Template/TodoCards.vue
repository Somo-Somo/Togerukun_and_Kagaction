<template>
    <div>
        <v-list class="py-0" width="100%">
            <v-col
                class="px-md-0"
                v-for="todo in selectTodoList"
                v-model="todo.showTodoList"
                :key="todo.uuid"
                :class="cardShow(todo) ? '' : 'd-none'"
                :style="
                    $vuetify.breakpoint.smAndUp
                        ? 'padding:8px 0px'
                        : 'padding:8px'
                "
            >
                <todo-card
                    :project="project"
                    :todo="todo"
                    :todoList="todoList"
                    @selectedMenu="selectedMenu"
                    @toTodoDetail="toTodoDetail"
                ></todo-card>
            </v-col>
            <div
                class="my-4"
                v-show="
                    !todoStatus.show &&
                    todoStatus.name !== 'ゴール' &&
                    todoStatus.name !== 'ToDo'
                "
            >
                <p
                    class="grey--text font-weight-bold ma-0 px-4 py-2"
                    :style="
                        $vuetify.breakpoint.smAndUp
                            ? 'font-size:16px;'
                            : 'font-size:14px;'
                    "
                >
                    {{ todoStatus.name }}はありません
                </p>
            </div>
        </v-list>
        <deleting-confirmation-dialog
            :deletingConfirmationDialog="deletingConfirmationDialog"
            :selectedDeletingItemName="selectedDeletingTodo.name"
            :loading="false"
            @deleteItem="deleteTodo"
            @onClickCancel="onClickCancel"
        />
    </div>
</template>

<script>
import TodoCard from "../Parts/Organisms/TodoCard.vue";
import DeletingConfirmationDialog from "../Parts/Molecules/DeletingConfirmationDialog.vue";

export default {
    components: {
        TodoCard,
        DeletingConfirmationDialog,
    },
    data: () => ({
        deletingConfirmationDialog: false,
        selectedDeletingTodo: { name: null },
    }),
    props: {
        project: {
            type: Object,
        },
        selectTodo: {
            type: Object,
        },
        todoList: {
            type: Array,
        },
        todoStatus: {
            type: Object,
        },
    },
    computed: {
        selectTodoList() {
            return this.todoStatus.name === "予定"
                ? this.sortScheduleList()
                : this.todoList;
        },
        cardShow() {
            return function (todo) {
                if (this.todoStatus.name === "予定") {
                    const today = new Date(
                        Date.now() - new Date().getTimezoneOffset() * 60000
                    )
                        .toISOString()
                        .substr(0, 10);
                    const diff =
                        (new Date(todo.date) - new Date(today)) /
                        (60 * 60 * 1000 * 24);
                    return todo.date && !(todo.accomplish && diff < 0)
                        ? this.showTodo()
                        : false;
                }
                if (this.todoStatus.name === "ToDo")
                    return this.selectTodo.uuid === todo.parentUuid
                        ? this.showTodo()
                        : false;

                return false;
            };
        },
    },
    methods: {
        showTodo() {
            this.todoStatus.show = true;
            return true;
        },
        async toTodoDetail(todo) {
            await this.$store.dispatch("todo/selectTodo", todo);
            return this.$router.push({ path: "/todo/" + todo.uuid });
        },
        async deleteTodo() {
            this.deletingConfirmationDialog = false;
            await this.$store.dispatch(
                "todo/deleteTodo",
                this.selectedDeletingTodo
            );
            this.selectedDeletingTodo = { name: null };
        },
        onClickCancel() {
            this.deletingConfirmationDialog = false;
            this.selectedDeletingTodo = { name: null };
        },
        sortScheduleList() {
            const scheduleList = [];
            for (const [key, todo] of Object.entries(this.todoList)) {
                if (todo.date) scheduleList.push(todo);
            }
            let sortScheduleList = scheduleList.sort(function (a, b) {
                return a.date < b.date ? -1 : 1; //オブジェクトの昇順ソート
            });
            return sortScheduleList;
        },
        selectedMenu(menuTitle, todo) {
            if (menuTitle === "削除") {
                this.deletingConfirmationDialog = true;
                this.selectedDeletingTodo = todo;
            }
        },
    },
    watch: {},
};
</script>
<style scoped lang="sass">
.toggleTransrateRight
  transform: rotate(0.25turn)

.v-icon.v-icon:after
  background-color: transparent !important
</style>
