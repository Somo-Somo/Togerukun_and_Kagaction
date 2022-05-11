<template>
    <div>
        <v-card
            class="overflow-y-auto"
            :class="todoList.length > 0 ? 'ma-4' : ''"
            style="max-height: calc(100vh - 160px)"
        >
            <v-list-item-group color="primary">
                <v-list-item
                    class="px-2"
                    :style="
                        $vuetify.breakpoint.smAndUp
                            ? 'height:80px'
                            : 'height:64px'
                    "
                    v-for="(todo, index) in todoList"
                    :key="index"
                    @click="toTodoDetail(todo)"
                    @mouseover="activeLine = index"
                    @mouseout="activeLine = false"
                    link
                >
                    <TodoTableLeftSideOfLine :todo="todo" />
                    <v-list-item-content class="px-3 py-0 ma-auto d-flex">
                        <TodoTableItemContent
                            :project="project"
                            :todo="todo"
                            :todoList="todoList"
                        />
                    </v-list-item-content>
                    <v-list-item-icon
                        class="align-self-center mx-0 px-2"
                        v-show="activeLine === index"
                    >
                        <Menu
                            :menus="menus"
                            :selectCard="todo"
                            @selectedMenu="selectedMenu"
                        />
                    </v-list-item-icon>
                </v-list-item>
                <v-list-item
                    class="d-flex justify-space-around px-3"
                    v-if="todoList.length > 0"
                    style="height:64px"
                    @click="$emit('onClickCreate')"
                    link
                >
                    <v-icon class="pa-1">mdi-plus</v-icon>
                    <v-list-item-content class="px-2">
                        <v-list-item-title>
                            <p
                                class="grey--text font-weight-bold ma-0 pa-1"
                                :style="
                                    $vuetify.breakpoint.smAndUp
                                        ? 'font-size:0.9rem'
                                        : 'font-size:0.75rem'
                                "
                            >
                                ゴールを追加する
                            </p>
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list-item-group>
        </v-card>
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
import TodoTableLeftSideOfLine from "./TodoTableLeftSideOfLine.vue";
import TodoTableItemContent from "./TodoTableItemContent.vue";
import Menu from "../Buttons/Menu.vue";
import DeletingConfirmationDialog from "../Dialog/DeletingConfirmationDialog.vue";

export default {
    components: {
        TodoTableLeftSideOfLine,
        TodoTableItemContent,
        Menu,
        DeletingConfirmationDialog,
    },
    data: () => ({
        activeLine: false,
        menus: [{ title: "削除", color: "color: red" }],
        deletingConfirmationDialog: false,
        selectedDeletingTodo: { name: null },
    }),
    props: {
        project: {
            type: Object,
        },
        todoList: {
            type: Array,
        },
    },
    computed: {},
    methods: {
        async toTodoDetail(todo) {
            await this.$store.dispatch("todo/selectTodo", todo);
            return this.$router.push({ path: "/todo/" + todo.uuid });
        },
        selectedMenu(menuTitle, todo) {
            if (menuTitle === "削除") {
                this.deletingConfirmationDialog = true;
                this.selectedDeletingTodo = todo;
            }
        },
        onClickCancel() {
            this.deletingConfirmationDialog = false;
            this.selectedDeletingTodo = { name: null };
        },
        deleteTodo() {
            this.deletingConfirmationDialog = false;
            this.$store.dispatch("todo/deleteTodo", this.selectedDeletingTodo);
            this.selectedDeletingTodo = { name: null };
        },
    },
};
</script>
