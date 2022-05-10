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
                <v-list-item-content class="px-3 py-0 ma-auto d-flex">
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
    computed: {},
    methods: {
        async toTodoDetail (todo) {
            await this.$store.dispatch("todo/selectTodo", todo);
            return this.$router.push({ path: "/todo/" + todo.uuid });
        },
    },
};
</script>
