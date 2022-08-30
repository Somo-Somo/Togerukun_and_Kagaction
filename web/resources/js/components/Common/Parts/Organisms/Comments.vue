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
                        <Menu
                            :menus="menus"
                            :selectCard="comment"
                            @selectedMenu="selectedMenu"
                        />
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
import Comment from "../Molecules/Comment.vue";
import DeletingConfirmationDialog from "../components/Dialog/DeletingConfirmationDialog.vue";
import Menu from "../components/Buttons/Menu.vue";
export default {
    components: {
        Comment,
        DeletingConfirmationDialog,
        Menu,
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
        getInitial(comment) {
            return comment.user_name.charAt(0);
        },
        getDateAndTime(comment) {
            const dateAndTime = new Date(comment.created_at);
            const month = dateAndTime.getMonth() + 1;
            const date = dateAndTime.getDate();
            const hours = dateAndTime.getHours().toString().padStart(2, "0");
            const minutes = dateAndTime
                .getMinutes()
                .toString()
                .padStart(2, "0");
            return month + "月" + date + "日 " + hours + ":" + minutes;
        },
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
