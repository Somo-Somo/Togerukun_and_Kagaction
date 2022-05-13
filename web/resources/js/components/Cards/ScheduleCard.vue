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
                        ? 'padding:12px 0px'
                        : 'padding:8px'
                "
            >
                <div class="d-flex">
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
                                style="width: 100%"
                                @click="toTodoDetail(todo)"
                                link
                            >
                                <v-list-item-content class="pa-0 d-flex">
                                    <div style="width: 100%">
                                        <v-list-item-subtitle
                                            class="d-flex align-content-start mt-3 mb-1"
                                        >
                                            <div
                                                class="d-flex pr-1"
                                                :style="
                                                    subTitle(todo)
                                                        .backgroundColor
                                                "
                                            >
                                                <v-icon size="8" color="#212121"
                                                    >mdi-clock-outline</v-icon
                                                >
                                                <p
                                                    class="ma-0 px-2 #212121--text font-weight-bold align-self-center"
                                                    :style="
                                                        $vuetify.breakpoint
                                                            .smAndUp
                                                            ? 'font-size:12px'
                                                            : 'font-size:8px'
                                                    "
                                                >
                                                    {{ subTitle(todo).title }}
                                                </p>
                                            </div>
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
                                                    」のToDo
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
                                                        top: 28px;
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
import DeletingConfirmationDialog from "../Dialog/DeletingConfirmationDialog.vue";

export default {
    components: {
        DeletingConfirmationDialog,
    },
    data: () => ({
        deletingConfirmationDialog: false,
        selectedDeletingTodo: null,
        cardMenu: [{ title: "削除", color: "color: red" }],
        subtitle: {
            date: {
                icon: "mdi-clock-outline",
                iconSize: 14,
                iconColor: "#212121",
                fontColor: "#212121--text",
            },
        },
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
        subTitle() {
            return (todo) => {
                return this.calcDate(todo);
            };
        },
        parentName() {
            return (todo) => {
                return " 「" + this.projectList[todo.projectUuid].name;
            };
        },
        showCard() {
            return (todo) => {
                const today = new Date(
                    Date.now() - new Date().getTimezoneOffset() * 60000
                )
                    .toISOString()
                    .substr(0, 10);
                const diff =
                    (new Date(todo.date) - new Date(today)) /
                    (60 * 60 * 1000 * 24);
                let showCard = false;
                if (this.period.name === "今日") {
                    showCard = diff === 0 ? true : false;
                }
                if (this.period.name === "7日以内") {
                    showCard = 0 <= diff && diff < 8 ? true : false;
                }
                if (this.period.name === "全期間") {
                    showCard = true;
                }
                if (this.period.name === "期限切れ") {
                    showCard = !todo.accomplish && diff < 0 ? true : false;
                }
                // 選択されたタブに表示できるカードがあるかのチェック
                if (showCard) this.existCard = true;
                return showCard;
            };
        },
        noCard() {
            return (period) => {
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
            this.selectedDeletingTodo = null;
        },
        onClickCancel() {
            this.deletingConfirmationDialog = false;
            this.selectedDeletingTodo = null;
        },
        calcDate(todo) {
            const today = new Date(
                Date.now() - new Date().getTimezoneOffset() * 60000
            )
                .toISOString()
                .substr(0, 10);
            const diff =
                (new Date(todo.date) - new Date(today)) / (60 * 60 * 1000 * 24);
            if (diff > 0) {
                this.subtitle.date.title = "残り" + diff + "日";
                this.subtitle.date.backgroundColor =
                    diff < 4 ? "background-color: yellow" : null;
            } else if (diff === 0) {
                this.subtitle.date.title = "今日";
                this.subtitle.date.backgroundColor =
                    "background-color: skyblue";
            } else {
                this.subtitle.date.title = Math.abs(diff) + "日経過";
                this.subtitle.date.backgroundColor = "background-color: coral";
            }
            return this.subtitle.date;
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
