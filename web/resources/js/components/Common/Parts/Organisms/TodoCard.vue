<template>
    <div class="d-flex">
        <!-- 予定 -->
        <v-card
            class="rounded"
            style="width: 100%"
            outlined
            @click="onClickCard(todo)"
            link
        >
            <v-list
                class="py-0 d-flex align-content-center"
                :style="
                    $vuetify.breakpoint.smAndUp ? 'height:80px' : 'height:64px'
                "
            >
                <v-list-item class="d-flex px-0" style="width: 100%">
                    <v-list-item-action
                        class="d-flex px-4 ma-auto"
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
                    <v-list-item-content class="d-flex">
                        <todo-title-and-sub-title
                            :todoTitle="todo.name"
                            :todoSubTitle="todoSubTitle"
                            :dateDetail="dateDetail"
                            :dateTitle="dateTitle"
                        ></todo-title-and-sub-title>
                    </v-list-item-content>
                    <v-list-item-icon class="align-self-center mx-0 px-4">
                        <ellipsis-menu
                            :menus="menus"
                            :selectCard="todo"
                            @selectedMenu="selectedMenu"
                        ></ellipsis-menu>
                    </v-list-item-icon>
                </v-list-item>
            </v-list>
        </v-card>
    </div>
</template>

<script>
import TodoTitleAndSubTitle from "../Molecules/TodoTitleAndSubTitle.vue";
import AccomplishBtn from "../Atom/Btn/AccomplishBtn.vue";
import EllipsisMenu from "../../../Common/Parts/Molecules/EllipsisMenu.vue";

export default {
    components: {
        TodoTitleAndSubTitle,
        AccomplishBtn,
        EllipsisMenu,
    },
    data: () => ({
        deletingConfirmationDialog: false,
        selectedDeletingTodo: { name: null },
        menus: [{ title: "削除", color: "color: red" }],
        date: {
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
        todo: {
            type: Object,
        },
        todoList: {
            type: Array,
        },
    },
    computed: {
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
        dateTitle() {
            return this.todo.date ? this.switchingDateTitle(this.todo) : null;
        },
        dateDetail() {
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
                const date = new Date(todo.date).getDate();
                return new Date().getFullYear() === year
                    ? month + "月" + date + "日"
                    : year + "年" + month + "月" + date + "日";
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
        selectedMenu(menuTitle, todo) {
            this.$emit("selectedMenu", menuTitle, todo);
        },
        onClickAccomplish(todo) {
            this.$set(todo, "accomplish", todo.accomplish ? false : true);
            this.$store.dispatch("todo/updateAccomplish", todo);
        },
        onClickCard(todo) {
            this.$emit("toTodoDetail", todo);
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
