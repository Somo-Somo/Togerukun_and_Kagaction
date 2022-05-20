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
                <div class="d-flex">
                    <!-- 予定 -->
                    <v-card class="rounded" style="width: 100%" outlined>
                        <v-list
                            class="py-0 d-flex align-content-center"
                            :style="
                                $vuetify.breakpoint.smAndUp
                                    ? 'height:80px'
                                    : 'height:64px'
                            "
                        >
                            <v-list-item
                                class="px-0"
                                style="width: 100%"
                                @click="toTodoDetail(todo)"
                                link
                            >
                                <v-list-item-action
                                    class="d-flex px-4 ma-auto"
                                    style="height: 24px"
                                    @click.stop="onClickAccomplish(todo)"
                                >
                                    <v-btn
                                        icon
                                        height="24"
                                        width="24"
                                        :color="todo.accomplish ? 'green' : ''"
                                    >
                                        <v-icon
                                            >mdi-checkbox-marked-circle-outline</v-icon
                                        >
                                    </v-btn>
                                </v-list-item-action>
                                <v-list-item-content class="pa-0 d-flex">
                                    <div style="width: 100%">
                                        <v-list-item-subtitle
                                            class="d-flex align-content-start mt-3 mb-1"
                                        >
                                            <DateSubTitle :todo="todo" />
                                            <div
                                                class="d-flex"
                                                style="max-width: 66%"
                                            >
                                                <p
                                                    class="ma-0 grey--text font-weight-bold align-self-center"
                                                    style="
                                                        font-size: 8px;
                                                        max-width: 100%;
                                                        white-space: nowrap;
                                                        overflow: hidden;
                                                        text-overflow: ellipsis;
                                                    "
                                                >
                                                    {{ parentName(todo) }}
                                                </p>
                                                <p
                                                    class="ma-0 grey--text font-weight-bold align-self-center"
                                                    style="font-size: 8px"
                                                >
                                                    {{ parentType(todo) }}
                                                </p>
                                            </div>
                                        </v-list-item-subtitle>
                                        <v-list-item-title class="py-2 pb-4">
                                            <p
                                                class="font-weight-black ma-0"
                                                style="
                                                    max-width: calc(
                                                        100% - 36px
                                                    );
                                                    white-space: nowrap;
                                                    overflow: hidden;
                                                    text-overflow: ellipsis;
                                                "
                                                :style="
                                                    $vuetify.breakpoint.smAndUp
                                                        ? 'font-size:1rem'
                                                        : 'font-size:0.8rem'
                                                "
                                            >
                                                {{ todo.name }}
                                            </p></v-list-item-title
                                        >
                                        <v-menu
                                            class="rounded-lg elevation-0"
                                            offset-y
                                        >
                                            <template
                                                v-slot:activator="{ on, attrs }"
                                            >
                                                <v-list-item-action
                                                    class="ma-0"
                                                    style="
                                                        position: absolute;
                                                        right: 16px;
                                                    "
                                                    :style="
                                                        $vuetify.breakpoint
                                                            .smAndUp
                                                            ? 'top: 28px;'
                                                            : 'top: 24px;'
                                                    "
                                                >
                                                    <v-btn
                                                        v-bind="attrs"
                                                        v-on="on"
                                                        small
                                                        icon
                                                        link
                                                    >
                                                        <v-icon
                                                            :size="
                                                                $vuetify
                                                                    .breakpoint
                                                                    .smAndUp
                                                                    ? '24'
                                                                    : '20'
                                                            "
                                                        >
                                                            mdi-dots-vertical
                                                        </v-icon>
                                                    </v-btn>
                                                </v-list-item-action>
                                            </template>
                                            <v-list>
                                                <v-list-item
                                                    v-for="menu in cardMenu"
                                                    :key="menu.title"
                                                    @click="
                                                        selectMenu(
                                                            menu.title,
                                                            todo
                                                        )
                                                    "
                                                    link
                                                >
                                                    <v-list-item-title
                                                        :style="menu.color"
                                                        >{{
                                                            menu.title
                                                        }}</v-list-item-title
                                                    >
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
import DateSubTitle from "../Date/DateSubTitle.vue";
import DeletingConfirmationDialog from "../Dialog/DeletingConfirmationDialog.vue";

export default {
    components: {
        DateSubTitle,
        DeletingConfirmationDialog,
    },
    data: () => ({
        deletingConfirmationDialog: false,
        selectedDeletingTodo: { name: null },
        cardMenu: [{ title: "削除", color: "color: red" }],
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
        parentName() {
            return (todo) => {
                if (todo.depth === 0) {
                    return "「" + this.project.name;
                } else if (todo.depth > 0) {
                    let parentName;
                    this.todoList.map((value) => {
                        if (todo.parentUuid === value.uuid)
                            parentName = value.name;
                    });
                    return "「" + parentName;
                }
            };
        },
        parentType() {
            return (todo) => {
                if (todo.depth === 0) return "」のゴール";
                if (todo.depth > 0) return "」のためのToDo";
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
        selectMenu(menuTitle, todo) {
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
            this.selectedDeletingTodo = { name: null };
        },
        onClickCancel() {
            this.deletingConfirmationDialog = false;
            this.selectedDeletingTodo = { name: null };
        },
        onClickAccomplish(todo) {
            this.$set(todo, "accomplish", todo.accomplish ? false : true);
            this.$store.dispatch("todo/updateAccomplish", todo);
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
    },
    watch: {
        todoList(next, prev) {
            return;
        },
    },
};
</script>
<style scoped lang="sass">
.toggleTransrateRight
  transform: rotate(0.25turn)

.v-icon.v-icon:after
  background-color: transparent !important
</style>
