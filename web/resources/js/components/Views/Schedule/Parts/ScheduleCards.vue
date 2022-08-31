<template>
    <div>
        <v-list class="py-0" width="100%">
            <v-col
                class="px-md-0"
                v-for="todo in scheduleList"
                v-show="showCard(todo)"
                :key="todo.uuid"
                :style="
                    $vuetify.breakpoint.smAndUp
                        ? 'padding:8px 0px'
                        : 'padding:8px'
                "
            >
                <todo-card
                    :todo="todo"
                    :todoList="scheduleList"
                    :project="project(todo)"
                    @selectedMenu="selectedMenu"
                    @toTodoDetail="toTodoDetail"
                ></todo-card>
            </v-col>
            <div class="my-4" v-show="!existCard && !loading">
                <p
                    class="grey--text font-weight-bold ma-0 pa-md-2 px-4 py-2"
                    :style="
                        $vuetify.breakpoint.smAndUp
                            ? 'font-size:16px;'
                            : 'font-size:14px;'
                    "
                >
                    {{ noCard(period) }}
                </p>
            </div>
        </v-list>
        <DeletingConfirmationDialog
            :deletingConfirmationDialog="deletingConfirmationDialog"
            :selectedDeletingItem="selectedDeletingTodo"
            :loading="false"
            @deleteItem="deleteTodo"
            @onClickCancel="onClickCancel"
        />
    </div>
</template>

<script>
import TodoCard from "../../../Common/Parts/Organisms/TodoCard.vue";
import DeletingConfirmationDialog from "../../../Common/Parts/Molecules/DeletingConfirmationDialog.vue";

export default {
    components: {
        TodoCard,
        DeletingConfirmationDialog,
    },
    data: () => ({
        deletingConfirmationDialog: false,
        selectedDeletingTodo: null,
        existCard: false,
    }),
    props: {
        projectList: {
            type: Object,
        },
        scheduleList: {
            type: Array,
        },
        period: {
            type: Object,
        },
        loading: {
            type: Boolean,
        },
    },
    computed: {
        project() {
            return (todo) => {
                return this.projectList[todo.projectUuid];
            };
        },
        showCard() {
            return (todo) => {
                let showCard = false;
                if (this.period) {
                    const today = new Date(
                        Date.now() - new Date().getTimezoneOffset() * 60000
                    )
                        .toISOString()
                        .substr(0, 10);
                    const diff =
                        (new Date(todo.date) - new Date(today)) /
                        (60 * 60 * 1000 * 24);
                    if (this.period.name === "今日") {
                        showCard = diff === 0 ? true : false;
                    }
                    if (this.period.name === "7日以内") {
                        showCard = 0 <= diff && diff < 8 ? true : false;
                    }
                    if (this.period.name === "全期間") {
                        showCard =
                            todo.date && !(todo.accomplish && diff < 0)
                                ? true
                                : false;
                    }
                    if (this.period.name === "期限切れ") {
                        showCard = !todo.accomplish && diff < 0 ? true : false;
                    }
                    // 選択されたタブに表示できるカードがあるかのチェック
                    if (showCard) this.existCard = true;
                }
                return showCard;
            };
        },
        noCard() {
            return (period) => {
                if (period) {
                    if (period.name === "今日") {
                        return "今日予定しているToDoはありません";
                    }
                    if (period.name === "7日以内") {
                        return "7日以内に予定しているToDoはありません";
                    }
                    if (period.name === "全期間") {
                        return "予定しているToDoはありません";
                    }
                    if (period.name === "期限切れ") {
                        return "期限切れのToDoはありません";
                    }
                }
            };
        },
    },
    methods: {
        async toTodoDetail(todo) {
            this.$store.dispatch(
                "project/selectProject",
                this.projectList[todo.projectUuid]
            );
            await this.$store.dispatch("todo/selectTodo", todo);
            return this.$router.push({ path: "/todo/" + todo.uuid });
        },
        selectedMenu(menuTitle, todo) {
            if (menuTitle === "削除") {
                this.deletingConfirmationDialog = true;
                this.selectedDeletingTodo = todo;
            }
        },
        async deleteTodo() {
            this.deletingConfirmationDialog = false;
            await this.$store.dispatch(
                "todo/deleteTodo",
                this.selectedDeletingTodo
            );
            this.selectedDeletingTodo = null;
        },
        onClickCancel() {
            this.deletingConfirmationDialog = false;
            this.selectedDeletingTodo = null;
        },
        onClickAccomplish(todo) {
            this.$set(todo, "accomplish", todo.accomplish ? false : true);
            this.$store.dispatch("todo/updateAccomplish", todo);
        },
    },
    watch: {
        period(next, prev) {
            this.existCard = false;
            return;
        },
    },
};
</script>
