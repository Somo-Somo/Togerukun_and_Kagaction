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
                    <TodoTableItemContent :project="project" :todo="todo" :todoList="todoList"/>
                </v-list-item-content>
            </v-list-item>
        </v-list-item-group>
    </v-card>
</template>

<script>
import DashedLine from "../DashedLine/DashedLine.vue";
import LDashedLine from "../DashedLine/LDashedLine.vue";
import TDashedLine from "../DashedLine/TDashedLine.vue";
import UpperOrLowerDashedLine from "../DashedLine/UpperOrLowerDashedLine.vue";
import TodoTableItemContent from "./TodoTableItemContent.vue";

export default {
    components: {
        DashedLine,
        LDashedLine,
        TDashedLine,
        UpperOrLowerDashedLine,
        TodoTableItemContent,
    },
    data: () => ({}),
    props: {
        project: {
            type: Object,
        },
        todoList: {
            type: Array,
        },
    },
    computed: {
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
    methods: {},
};
</script>
