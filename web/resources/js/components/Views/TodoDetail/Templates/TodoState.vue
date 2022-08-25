<template>
    <div class="d-flex flex-column">
        <todo-text-area :todo="todo" :parentTodo="parentTodo"></todo-text-area>
        <div class="d-flex px-1">
            <todo-accomplish
                :todo="todo"
                @onClickAccomplish="onclickAccomplish"
            ></todo-accomplish>
            <todo-date
                :todo="todo"
                @onClickSave="updateDate"
                @onClickRemove="removeDate"
            ></todo-date>
        </div>
    </div>
</template>

<script>
import TodoTextArea from "../Parts/TodoTextArea.vue";
import TodoAccomplish from "../Parts/TodoAccomplish.vue";
import TodoDate from "../Parts/TodoDate.vue";

export default {
    components: {
        TodoTextArea,
        TodoAccomplish,
        TodoDate,
    },
    data: () => ({
        form: false,
        date: null,
    }),
    props: {
        todo: {
            type: Object,
        },
        parentTodo: {
            type: Object,
        },
    },
    computed: {},
    methods: {
        onClickAccomplish(todo) {
            this.$store.dispatch("todo/updateAccomplish", todo);
        },
        updateDate(date) {
            this.$store.dispatch("todo/updateDate", {
                date: date,
                todo: this.todo,
            });
        },
        removeDate() {
            this.$store.dispatch("todo/updateDate", {
                date: null,
                todo: this.todo,
            });
        },
    },
};
</script>

<style scoped lang="sass"></style>
