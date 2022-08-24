<template>
    <div>
        <v-card
            class="overflow-y-auto"
            :class="todoList.length > 0 ? 'mx-4 my-2' : ''"
            style="max-height: calc(100vh - 160px)"
        >
            <v-list-item-group color="primary">
                <v-list-item
                    class="px-2"
                    :style="
                        $vuetify.breakpoint.smAndUp
                            ? 'height:80px'
                            : 'height:64px'
                    "
                    v-for="(todo, index) in todoList"
                    :key="index"
                    @click="toTodoDetail(todo)"
                    @mouseover="activeLine = index"
                    @mouseout="activeLine = false"
                    link
                >
                    <todo-table-row
                        :project="project"
                        :todo="todo"
                        :todoList="todoList"
                        :showMenuBtn="activeLine === index ? true : false"
                        @selectedMenu="selectedMenu"
                    ></todo-table-row>
                </v-list-item>
                <additional-goal-table-row
                    v-if="todoList.length > 0"
                    @click="$emit('onClickCreate')"
                >
                </additional-goal-table-row>
            </v-list-item-group>
        </v-card>
        <deleting-confirmation-dialog
            :deletingConfirmationDialog="deletingConfirmationDialog"
            :selectedDeletingItemName="selectedDeletingTodo.name"
            :loading="false"
            @deleteItem="deleteTodo"
            @onClickCancel="onClickCancel"
        />
    </div>
</template>

<script>
import TodoTableRow from "../Parts/TodoTableRow.vue";
import AdditionalGoalTableRow from "../Parts/AdditionalGoalTableRow.vue";
import DeletingConfirmationDialog from "../../../Common/Parts/Molecules/DeletingConfirmationDialog.vue";

export default {
    components: {
        TodoTableRow,
        AdditionalGoalTableRow,
        DeletingConfirmationDialog,
    },
    data: () => ({
        activeLine: false,
        deletingConfirmationDialog: false,
        selectedDeletingTodo: { name: null },
    }),
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
        async toTodoDetail(todo) {
            await this.$store.dispatch("todo/selectTodo", todo);
            return this.$router.push({ path: "/todo/" + todo.uuid });
        },
        onClickCancel() {
            this.deletingConfirmationDialog = false;
            this.selectedDeletingTodo = { name: null };
        },
        selectedMenu(menuTitle, todo) {
            if (menuTitle === "削除") {
                this.deletingConfirmationDialog = true;
                this.selectedDeletingTodo = todo;
            }
        },
        deleteTodo() {
            this.deletingConfirmationDialog = false;
            this.$store.dispatch("todo/deleteTodo", this.selectedDeletingTodo);
            this.selectedDeletingTodo = { name: null };
        },
    },
};
</script>
