<template>
    <div>
        <v-list class="py-0" width="100%">
            <v-col class="px-0 py-1">
                <div class="d-flex">
                    <!-- 予定 -->
                    <v-list
                        v-if="todo.causes.length > 0"
                        class="py-0 align-content-center"
                        style="width: 100%"
                    >
                        <v-list-item
                            class="px-0"
                            style="width: 100%"
                            v-for="(cause, index) in todo.causes"
                            @mouseover="causeIndex = index"
                            @mouseout="causeIndex = false"
                            :key="index"
                            link
                        >
                            <v-list-item-action
                                class="px-4 ma-auto"
                                style="height: 24px"
                            >
                                <v-icon>mdi-help-circle-outline</v-icon>
                            </v-list-item-action>
                            <v-list-item-content class="pa-0">
                                <div class="d-flex" style="width: 100%">
                                    <v-list-item-title class="py-auto">
                                        <p
                                            class="font-weight-black ma-0"
                                            style="
                                                font-size: 1rem;
                                                max-width: calc(100% - 36px);
                                                white-space: nowrap;
                                                overflow: hidden;
                                                text-overflow: ellipsis;
                                            "
                                        >
                                            {{ cause.text }}
                                        </p></v-list-item-title
                                    >
                                    <v-menu
                                        class="rounded-lg elevation-0"
                                        offset-y
                                    >
                                        <template
                                            v-slot:activator="{ on, attrs }"
                                        >
                                            <v-list-item-action class="ma-auto">
                                                <v-btn
                                                    v-bind="attrs"
                                                    v-on="on"
                                                    small
                                                    icon
                                                    link
                                                >
                                                    <v-icon size="24">
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
                                                    selectMenu(menu.title, todo)
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
                </div>
            </v-col>
        </v-list>
        <DeletingConfirmationDialog
            :deletingConfirmationDialog="deletingConfirmationDialog"
            :selectedDeletingItemName="selectedDeletingTodo.name"
            :loading="false"
            @deleteItem="deleteTodo"
            @onClickCancel="onClickCancel"
        />
    </div>
</template>

<script>
import DeletingConfirmationDialog from "../components/Dialog/DeletingConfirmationDialog.vue";

export default {
    components: {
        DeletingConfirmationDialog,
    },
    data: () => ({
        causeIndex: false,
        deletingConfirmationDialog: false,
        selectedDeletingTodo: { name: null },
        cardMenu: [{ title: "削除", color: "color: red" }],
    }),
    props: {
        todo: {
            type: Object,
        },
    },
    computed: {},
    methods: {
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
            this.selectedDeletingTodo = { name: null };
        },
        onClickCancel() {
            this.deletingConfirmationDialog = false;
            this.selectedDeletingTodo = { name: null };
        },
    },
};
</script>
<style scoped lang="sass">
.toggleTransrateRight
  transform: rotate(0.25turn)

.v-icon.v-icon:after
  background-color: transparent !important
</style>
