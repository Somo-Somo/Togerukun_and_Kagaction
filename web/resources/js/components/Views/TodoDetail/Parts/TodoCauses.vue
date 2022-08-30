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
                            <cause :cause="cause"></cause>
                            <div v-show="causeIndex === index">
                                <v-list-item-icon
                                    class="d-flex align-self-center ma-0"
                                >
                                    <ellipsis-menu
                                        :menus="menus"
                                        :selectCard="cause"
                                        @selectedMenu="selectedMenu"
                                    ></ellipsis-menu>
                                </v-list-item-icon>
                            </div>
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
import Cause from "../../../Common/Parts/Molecules/Cause.vue";
import DeletingConfirmationDialog from "../../../Common/Parts/Molecules/DeletingConfirmationDialog.vue";
import EllipsisMenu from "../../../Common/Parts/Molecules/EllipsisMenu.vue";

export default {
    components: {
        Cause,
        DeletingConfirmationDialog,
        EllipsisMenu,
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
