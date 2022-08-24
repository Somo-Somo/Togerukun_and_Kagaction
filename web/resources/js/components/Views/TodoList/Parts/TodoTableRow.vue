<template>
    <div class="d-flex" style="'position: relative; width: 100%;'">
        <div class="d-flex" style="max-height: 88px">
            <div class="d-flex">
                <goal-flag v-if="todo.depth === 0" />
                <empty-space
                    :style="'width: 50px;'"
                    v-if="todo.depth !== 0"
                ></empty-space>
            </div>
            <v-list-item-content
                class="d-flex py-0"
                v-for="num in todo.depth"
                :key="num"
            >
                <div class="d-flex">
                    <component
                        :is="currentComponent(num)"
                        :style="'width:50px; height:88px;'"
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
                    <accomplish-btn
                        :height="24"
                        :width="24"
                        :color="todo.accomplish ? 'green' : ''"
                    ></accomplish-btn>
                </v-list-item-action>
                <div class="d-flex mx-auto" v-if="todo.child" height="32">
                    <lower-half-dashed-line
                        :height="28"
                        :width="24"
                    ></lower-half-dashed-line>
                </div>
            </div>
        </div>
        <v-list-item-content class="px-3 py-0 ma-auto d-flex">
            <todo-title-and-sub-title
                :todoTitle="todo.name"
                :todoSubTitle="todoSubTitle"
                :date="todoDate"
            ></todo-title-and-sub-title>
        </v-list-item-content>
        <v-list-item-icon
            class="align-self-center mx-0 px-2"
            v-show="activeLine === index"
        >
            <ellipsis-menu
                :menus="menus"
                :selectCard="todo"
                @selectedMenu="selectedMenu"
            ></ellipsis-menu>
        </v-list-item-icon>
    </div>
</template>

<script>
import GoalFlag from "../../../Common/Parts/Atom/GoalFlag.vue";
import TodoTitleAndSubTitle from "../../../Common/Parts/Molecules/TodoTitleAndSubTitle.vue";
import EllipsisMenu from "../../../Common/Parts/Molecules/EllipsisMenu.vue";
import EmptySpace from "../../../Common/Parts/Atom/Spacer.vue";
import TShapedDashedLine from "../../../Common/Parts/Atom/DashedLine/TShapedDashedLine.vue";
import LShapedDashedLine from "../../../Common/Parts/Atom/DashedLine/LShapedDashedLine.vue";
import LowerHalfDashedLine from "../../../Common/Parts/Atom/DashedLine/LowerHalfDashedLine.vue";
import DashedLine from "../../../Common/Parts/Atom/DashedLine/DashedLine.vue";
import AccomplishBtn from "../../../Common/Parts/Atom/Btn/AccomplishBtn.vue";

export default {
    components: {
        GoalFlag,
        TodoTitleAndSubTitle,
        EllipsisMenu,
        EmptySpace,
        TShapedDashedLine,
        LShapedDashedLine,
        LowerHalfDashedLine,
        DashedLine,
        AccomplishBtn,
    },
    data: () => ({
        activeLine: false,
        menus: [{ title: "削除", color: "color: red" }],
        deletingConfirmationDialog: false,
        selectedDeletingTodo: { name: null },
        date: {
            title: null,
            icon: "mdi-clock-outline",
            iconSize: 14,
            iconColor: "#212121",
            fontColor: "#212121--text",
            backGroundColor: "background-color: null;",
        },
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
                if (this.todo.depth === num) {
                    return this.todo.leftSideOfLine[num].lastChild
                        ? "l-shaped-dashed-line"
                        : "t-shaped-dashed-line";
                } else {
                    return this.todo.leftSideOfLine[num].lastChild
                        ? "empty-space"
                        : "dashed-line";
                }
            };
        },
        todoSubTitle() {
            if (this.todo.depth === 0) {
                return "「" + this.project.name + "」のゴール";
            } else if (this.todo.depth > 0) {
                let parentName;
                this.todoList.map((value) => {
                    if (this.todo.parentUuid === value.uuid)
                        parentName = value.name;
                });
                return "「" + parentName + "」のためのToDo";
            }
        },
        todoDate() {
            this.date.title = this.switchingDateTitle(this.todo);
            this.date.backGroundColor = this.switchingDateBackGroundColor(
                this.todo
            );
            return this.date;
        },
    },
    methods: {
        switchingDateTitle(todo) {
            const diff = this.calcDateDiff(todo);
            if (diff > 0) {
                return "残り" + diff + "日";
            } else if (diff === 0) {
                return "今日";
            } else if (todo.accomplish) {
                const year = new Date(todo.date).getFullYear();
                const month = new Date(todo.date).getMonth() + 1;
                const day = new Date(todo.date).getDay() + 1;
                return new Date().getFullYear() === year
                    ? month + "月" + day + "日"
                    : year + "年" + month + "月" + day + "日";
            } else {
                return Math.abs(diff) + "日経過";
            }
        },
        switchingDateBackGroundColor(todo) {
            const diff = this.calcDateDiff(todo);
            if (diff > 0) {
                return diff < 4
                    ? "background-color: yellow"
                    : "background-color: transparent";
            } else if (diff === 0) {
                return "background-color: skyblue";
            } else if (todo.accomplish) {
                return "background-color: transparent;";
            } else {
                return "background-color: coral";
            }
        },
        calcDateDiff(todo) {
            const today = new Date(
                Date.now() - new Date().getTimezoneOffset() * 60000
            )
                .toISOString()
                .substr(0, 10);
            const diff =
                (new Date(todo.date) - new Date(today)) / (60 * 60 * 1000 * 24);
            return diff;
        },
        async toTodoDetail(todo) {
            await this.$store.dispatch("todo/selectTodo", todo);
            return this.$router.push({ path: "/todo/" + todo.uuid });
        },
        onClickAccomplish(todo) {
            this.$set(todo, "accomplish", todo.accomplish ? false : true);
            this.$store.dispatch("todo/updateAccomplish", todo);
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
