<template>
    <v-list-item>
        <div class="d-flex" style="max-height: 88px">
            <div class="d-flex">
                <goal-flag v-if="todo.depth === 0" />
                <spacer :style="'width: 50px'" v-if="todo.depth !== 0" />
            </div>
            <v-list-item-content
                class="d-flex py-0"
                v-for="num in todo.depth"
                :key="num"
            >
                <div class="d-flex">
                    <component
                        :is="currentComponent"
                        :style="'width: 50px; height: 88px;'"
                    ></component>
                </div>
            </v-list-item-content>
            <div class="d-flex flex-column" style="width: 28px; height: 88px">
                <v-list-item-action
                    class="d-flex mx-auto"
                    :class="todo.child ? 'mt-auto mb-1' : 'my-auto'"
                    style="height: 24px"
                    v-model="todo.accomplish"
                    @click.stop="onClickAccomplish(todo)"
                >
                    <v-btn
                        icon
                        height="24"
                        width="24"
                        :color="todo.accomplish ? 'green' : ''"
                    >
                        <v-icon>mdi-checkbox-marked-circle-outline</v-icon>
                    </v-btn>
                </v-list-item-action>
                <div class="d-flex mx-auto" v-if="todo.child" height="32">
                    <LowerDashedLine />
                </div>
            </div>
        </div>
        <v-list-item-content class="px-3 py-0 ma-auto d-flex">
            <todo-table-item-content
                :project="project"
                :todo="todo"
                :todoList="todoList"
            />
        </v-list-item-content>
        <v-list-item-icon
            class="align-self-center mx-0 px-2"
            v-show="activeLine === index"
        >
            <menu
                :menus="menus"
                :selectCard="todo"
                @selectedMenu="selectedMenu"
            />
        </v-list-item-icon>
    </v-list-item>
</template>

<script>
import GoalFlag from "../../../CommonParts/Atom/GoalFlag.vue";
import TodoTableLeftSideOfLine from "./TodoTableLeftSideOfLine.vue";
import TodoTableItemContent from "./TodoTableItemContent.vue";
import Menu from "../Buttons/Menu.vue";
import Spacer from "../../../CommonParts/Atom/Spacer.vue";
import TShapedDashedLine from "../../../CommonParts/Atom/DashedLine/TShapedDashedLine.vue";
import LShapedDashedLine from "../../../CommonParts/Atom/DashedLine/LShapedDashedLine.vue";
import DashedLine from "../../../CommonParts/Atom/DashedLine/DashedLine.vue";

export default {
    components: {
        GoalFlag,
        TodoTableLeftSideOfLine,
        TodoTableItemContent,
        Menu,
        Spacer,
        TShapedDashedLine,
        LShapedDashedLine,
        DashedLine,
    },
    data: () => ({
        activeLine: false,
        menus: [{ title: "削除", color: "color: red" }],
        selectedDeletingTodo: { name: null },
    }),
    props: {
        project: {
            type: Object,
        },
        todoList: {
            type: Array,
        },
        todo: {
            type: Object,
        },
    },
    computed: {
        currentComponent() {
            return (num) => {
                if (todo.depth === num && !todo.leftSideOfLine[num].lastChild) {
                    return todo.leftSideOfLine[num].lastChild
                        ? "t-shaped-dashed-line"
                        : "l-shaped-dashed-line";
                } else {
                    return todo.leftSideOfLine[num].lastChild
                        ? "spacer"
                        : "dashed-line";
                }
            };
        },
    },
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
    },
};
</script>
