<template>
    <v-card class="ma-4">
        <v-list-item-group color="primary">
            <v-list-item
                class="px-0"
                :style="
                    $vuetify.breakpoint.smAndUp ? 'height:88px' : 'height:64px'
                "
                v-for="(todo, i) in todoList"
                :key="i"
                link
            >
                <div class="d-flex">
                    <TDashedLine />
                </div>
                <div class="d-flex">
                    <div
                        class="d-flex align-content-center pa-2"
                    >
                        <svg style="width:28px;height:28px" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M14.4,6H20V16H13L12.6,14H7V21H5V4H14L14.4,6M14,14H16V12H18V10H16V8H14V10L13,8V6H11V8H9V6H7V8H9V10H7V12H9V10H11V12H13V10L14,12V14M11,10V8H13V10H11M14,10H16V12H14V10Z" />
                        </svg>
                    </div>
                </div>
                <div class="d-flex ma-auto" style="width: 24px; height: 80px">
                    <div
                        class="d-flex align-content-center ma-auto"
                        style="height: 24px"
                    >
                        <v-btn icon height="24" width="24">
                            <v-icon>mdi-checkbox-marked-circle-outline</v-icon>
                        </v-btn>
                    </div>
                </div>
                <v-list-item-content class="px-2 py-0 ma-auto d-flex">
                    <div>
                        <v-list-item-subtitle
                            class="d-flex align-content-start mt-3 mb-1"
                        >
                            <div
                                class="d-flex"
                                :style="subTitle(todo).backgroundColor"
                                v-if="subTitle(todo)"
                            >
                                <v-icon
                                    :size="subTitle(todo).iconSize"
                                    :color="subTitle(todo).iconColor"
                                    >{{ subTitle(todo).icon }}</v-icon
                                >
                                <p
                                    class="ma-0 px-2 font-weight-bold align-self-center"
                                    :class="subTitle(todo).fontColor"
                                    :style="
                                        $vuetify.breakpoint.smAndUp
                                            ? 'font-size:12px'
                                            : 'font-size:8px'
                                    "
                                >
                                    {{ subTitle(todo).title }}
                                </p>
                            </div>
                            <div class="d-flex" style="max-width: 66%">
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
                                    max-width: calc(100% - 36px);
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
                        <v-menu class="rounded-lg elevation-0" offset-y>
                            <template v-slot:activator="{ on, attrs }">
                                <v-list-item-action
                                    class="ma-0"
                                    style="position: absolute; right: 16px"
                                    :style="
                                        $vuetify.breakpoint.smAndUp
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
                                                $vuetify.breakpoint.smAndUp
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
                                    @click="selectMenu(menu.title, todo)"
                                    link
                                >
                                    <v-list-item-title :style="menu.color">{{
                                        menu.title
                                    }}</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </div>
                </v-list-item-content>
            </v-list-item>
        </v-list-item-group>
    </v-card>
</template>

<script>
import DashedLine from "../components/DashedLine/DashedLine.vue";
import LDashedLine from "../components/DashedLine/LDashedLine.vue";
import TDashedLine from "../components/DashedLine/TDashedLine.vue";
import UpperOrLowerDashedLine from "../components/DashedLine/UpperOrLowerDashedLine.vue";

export default {
    components: {
        DashedLine,
        LDashedLine,
        TDashedLine,
        UpperOrLowerDashedLine,
    },
    data: () => ({
        todoStatus: {
            accomplish: {
                icon: "mdi-circle",
                iconSize: 8,
                title: "完了",
                backgroundColor: "background-color: null",
                iconColor: "green",
                fontColor: "#212121--text",
            },
            date: {
                icon: "mdi-clock-outline",
                iconSize: 14,
                iconColor: "#212121",
                fontColor: "#212121--text",
            },
        },
        cardMenu: [{ title: "削除", color: "color: red" }],
    }),
    props: {
        project: {
            type: Object,
        },
        todoList: {
            type: Array,
        },
    },
    computed: {
        subTitle() {
            return (todo) => {
                if (todo.accomplish) {
                    return this.todoStatus.accomplish;
                } else if (todo.date) {
                    return this.calcDate(todo);
                } else {
                    return false;
                }
            }
        },
        parentName() {
            return (todo) => {
                if (todo.depth === 0) {
                    return '「' + this.project.name;
                } else if (todo.depth > 0)  {
                    let parentName;
                    this.todoList.map((value) => {
                        if (todo.parentUuid === value.uuid) parentName =  value.name;
                    })
                    return '「' + parentName ;
                }
            }
        },
        parentType() {
            return (todo) => {
                if (todo.depth === 0)  return '」のゴール';
                if (todo.depth > 0) return '」のためのToDo';
            }
        },
        memo() {
            // max-depth
            // 1-1 ト＋下
            // 2-2  直+L+下
            // 3-3  直+1スペ+ト
            // 4-3  直+１スペ+L
            // 5-1  ト+下
            // 6-2  直+L
            // 7-1  L
            // 項目 start, second, lower, スペース
            // Dash
            // トの場合: depth === x && 下にdepth === xがある
            // Lの場合:　depth === x && 下にdepth === xがない
            // 直の場合: depth !== x && 下にdepth === xがある
            // スペの場合: depth !== x && 下にdepth === xがない
            // Lower
            // ある: 下にx < y がある
            // なし: 下にx < y がない
            // for todo in todos
            // for let x = 1 x < todos.max_depth+1 x++
            // トor直orLor下
            // 下に同じdepthがあるかないか
            // 子todoがあるかないか
        },
    },
    methods: {
        calcDate(todo) {
            const today = new Date(
                Date.now() - new Date().getTimezoneOffset() * 60000
            ).toISOString().substr(0, 10);
            const diff =
                (new Date(todo.date) - new Date(today)) / (60 * 60 * 1000 * 24);
            if (diff > 0) {
                this.todoStatus.date.title = "残り" + diff + "日";
                this.todoStatus.date.backgroundColor =
                    diff < 4 ? "background-color: yellow" : null;
            } else if (diff === 0) {
                this.todoStatus.date.title = "今日";
                this.todoStatus.date.backgroundColor =
                    "background-color: skyblue";
            } else {
                this.todoStatus.date.title = Math.abs(diff) + "日経過";
                this.todoStatus.date.backgroundColor = "background-color: coral";
            }
            return this.todoStatus.date;
        },
    },
};
</script>
