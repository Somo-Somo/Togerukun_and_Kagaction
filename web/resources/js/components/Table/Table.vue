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
                @click="toTodoDetail(todo)"
                link
            >
                <TodoTableLeftSideOfLine :todo="todo" />
                <v-list-item-content class="px-4 py-0 ma-auto d-flex">
                    <TodoTableItemContent :project="project" :todo="todo" :todoList="todoList"/>
                </v-list-item-content>
            </v-list-item>
        </v-list-item-group>
    </v-card>
</template>

<script>
import TodoTableLeftSideOfLine from "./TodoTableLeftSideOfLine.vue";
import TodoTableItemContent from "./TodoTableItemContent.vue";

export default {
    components: {
        TodoTableLeftSideOfLine,
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
    methods: {
        async toTodoDetail (todo) {
            await this.$store.dispatch("todo/selectTodo", todo);
            return this.$router.push({ path: "/todo/" + todo.uuid });
        },
    },
};
</script>
