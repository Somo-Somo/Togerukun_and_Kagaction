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
                                    <div v-show="causeIndex === index">
                                        <v-list-item-icon
                                            class="d-flex align-self-center ma-0"
                                        >
                                            <Menu
                                                :menus="menus"
                                                :selectCard="cause"
                                                @selectedMenu="selectedMenu"
                                            />
                                        </v-list-item-icon>
                                    </div>
                                </div>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </div>
            </v-col>
        </v-list>
        <DeletingConfirmationDialog
            :deletingConfirmationDialog="deletingConfirmationDialog"
            :selectedDeletingItemName="selectedDeletingCause.text"
            :loading="false"
            @deleteItem="deleteCause"
            @onClickCancel="onClickCancel"
        />
    </div>
</template>

<script>
import DeletingConfirmationDialog from "../components/Dialog/DeletingConfirmationDialog.vue";
import Menu from "../components/Buttons/Menu.vue";
export default {
    components: {
        DeletingConfirmationDialog,
        Menu,
    },
    data: () => ({
        causeIndex: false,
        deletingConfirmationDialog: false,
        selectedDeletingCause: { text: null },
        menus: [{ title: "削除", color: "color: red" }],
    }),
    props: {
        todo: {
            type: Object,
        },
    },
    computed: {},
    methods: {
        selectedMenu(menuTitle, cause) {
            if (menuTitle === "削除") {
                this.deletingConfirmationDialog = true;
                this.selectedDeletingCause = cause;
            }
        },
        async deleteCause() {
            this.deletingConfirmationDialog = false;
            await this.$store.dispatch("todo/deleteCause", {
                todo: this.todo,
                cause: this.selectedDeletingCause,
            });
            this.selectedDeletingCause = { text: null };
        },
        onClickCancel() {
            this.deletingConfirmationDialog = false;
            this.selectedDeletingCause = { text: null };
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
