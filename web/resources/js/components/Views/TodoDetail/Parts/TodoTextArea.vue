<template>
    <div class="py-2 py-md-4 d-flex justify-start flex-column">
        <v-subheader
            class="pa-md-0 d-flex"
            :class="
                $vuetify.breakpoint.mdAndUp ? 'todoSubTitle' : 'spTodoSubTitle'
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
</template>

<script>
export default {
    data: () => ({}),
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
        updateTodoName() {
            if (this.todo.name) {
                this.$store.dispatch("todo/editTodo", this.todo);
            }
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
