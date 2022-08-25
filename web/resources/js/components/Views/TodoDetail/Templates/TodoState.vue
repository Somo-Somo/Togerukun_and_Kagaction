<template>
    <div class="d-flex flex-column">
        <todo-text-area :todo="todo" :parentTodo="parentTodo"></todo-text-area>
        <div class="d-flex px-1">
            <todo-accomplish
                :todo="todo"
                @onClickAccomplish="onclickAccomplish"
            ></todo-accomplish>
            <div
                class="py-2 ml-md-2 d-flex align-self-center"
                :style="
                    $vuetify.breakpoint.mdAndUp
                        ? 'height: 72px'
                        : 'height: 48px'
                "
            >
                <v-subheader class="d-flex align-self-center pa-md-0">
                    <p class="ma-0 font-weight-bold" color="grey darken-1">
                        日付 :
                    </p>
                </v-subheader>
                <v-col class="px-md-4 pa-0 d-flex align-self-center">
                    <Calender
                        :todo="todo"
                        :dateLabel="null"
                        @onClickSave="updateDate"
                        @onClickRemove="removeDate"
                    />
                </v-col>
            </div>
        </div>
    </div>
</template>

<script>
import TodoTextArea from "../Parts/TodoTextArea.vue";
import TodoAccomplish from "../Parts/TodoAccomplish.vue";
import Calender from "../components/Calender.vue";

export default {
    components: {
        TodoTextArea,
        TodoAccomplish,
        Calender,
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
