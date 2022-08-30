<template>
    <div>
        <v-list>
            <v-list-item
                class="d-flex px-1 py-2"
                v-for="(comment, index) in todo.comments"
                @mouseover="commentIndex = index"
                @mouseout="commentIndex = false"
                :key="index"
                link
            >
                <comment :comment="comment"></comment>
                <div
                    class="px-1"
                    style="position: absolute; right: 8px"
                    v-show="commentIndex === index"
                >
                    <v-list-item-icon class="d-flex align-self-center ma-0">
                        <ellipsis-menu
                            :menus="menus"
                            :selectCard="comment"
                            @selectedMenu="selectedMenu"
                        ></ellipsis-menu>
                    </v-list-item-icon>
                </div>
            </v-list-item>
        </v-list>
        <DeletingConfirmationDialog
            :deletingConfirmationDialog="deletingConfirmationDialog"
            :selectedDeletingItemName="selectedDeletingComment.text"
            :loading="false"
            @deleteItem="deleteComment"
            @onClickCancel="onClickCancel"
        />
    </div>
</template>

<script>
import Comment from "../../../Common/Parts/Molecules/Comment.vue";
import DeletingConfirmationDialog from "../../../Common/Parts/Molecules/DeletingConfirmationDialog.vue";
import EllipsisMenu from "../../../Common/Parts/Molecules/EllipsisMenu.vue";

export default {
    components: {
        Comment,
        DeletingConfirmationDialog,
        EllipsisMenu,
    },
    data: () => ({
        menus: [{ title: "削除", color: "color: red" }],
        commentIndex: false,
        deletingConfirmationDialog: false,
        selectedDeletingComment: { text: null },
    }),
    props: {
        todo: {
            type: Object,
        },
    },
    computed: {},
    methods: {
        selectedMenu(menuTitle, comment) {
            if (menuTitle === "削除") {
                this.deletingConfirmationDialog = true;
                this.selectedDeletingComment = comment;
            }
        },
        onClickCancel() {
            this.deletingConfirmationDialog = false;
            this.selectedDeletingTodo = { text: null };
        },
        async deleteComment() {
            this.deletingConfirmationDialog = false;
            await this.$store.dispatch("todo/deleteComment", {
                todo: this.todo,
                comment: this.selectedDeletingComment,
            });
            this.selectedDeletingTodo = { text: null };
        },
    },
};
</script>
