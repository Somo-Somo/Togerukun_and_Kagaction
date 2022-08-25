<template>
    <div class="d-flex flex-column">
        <div class="py-2 py-md-4 d-flex justify-start flex-column">
            <v-subheader
                class="pa-md-0 d-flex"
                :class="
                    $vuetify.breakpoint.mdAndUp
                        ? 'todoSubTitle'
                        : 'spTodoSubTitle'
                "
            >
                <p class="ma-0 font-weight-bold" color="grey darken-1">
                    {{ subHeader }}
                </p>
            </v-subheader>
            <v-textarea
                label="名前を入力"
                v-model="todo.name"
                @change="updateTodoName"
                class="pa-0"
                rows="1"
                :class="$vuetify.breakpoint.smAndUp ? 'text-h5' : 'text-h6'"
                auto-grow
                single-line
                solo
                flat
                hide-details
            ></v-textarea>
        </div>
        <div class="d-flex px-1">
            <div
                class="py-2 d-flex justify-start"
                :style="
                    $vuetify.breakpoint.mdAndUp
                        ? 'height: 72px'
                        : 'height: 48px'
                "
            >
                <v-subheader
                    class="d-flex align-self-center pa-md-0"
                    :class="
                        $vuetify.breakpoint.mdAndUp
                            ? 'todoSubTitle'
                            : 'spTodoSubTitle'
                    "
                >
                    <p
                        class="ma-0 font-weight-bold"
                        style="min-width: 36px"
                        color="grey darken-1"
                    >
                        達成：
                    </p>
                </v-subheader>
                <v-col class="px-4 py-0 d-flex align-self-center">
                    <v-checkbox
                        v-model="todo.accomplish"
                        @click="onClickAccomplish(todo)"
                    ></v-checkbox>
                </v-col>
            </div>
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
import Calender from "../components/Calender.vue";

export default {
    components: {
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
    computed: {
        subHeader() {
            return this.todo.depth === 0
                ? "ゴール"
                : "｢" + this.parentTodo.name + "｣ のためのToDo";
        },
    },
    methods: {
        onClickAccomplish(todo) {
            this.$store.dispatch("todo/updateAccomplish", todo);
        },
        updateTodoName() {
            if (this.todo.name) {
                this.$store.dispatch("todo/editTodo", this.todo);
            }
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

<style scoped lang="sass">
.todoSubTitle
  font-size: 1rem
  height: 36px

.spTodoSubTitle
  font-size: 12px
  height: 24px
  padding: 0 0 0 12px
</style>
