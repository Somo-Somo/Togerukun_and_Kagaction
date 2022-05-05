<template>
<div>
    <v-list>
        <v-list-item
            class="d-flex px-1 py-2"
            v-for="(comment, index) in hypothesis.comments"
            @mouseover="commentIndex = index"
            @mouseout="commentIndex = false"
            :key="index"
            link
        >
            <div class="d-flex">
                <v-list-item-avatar
                    class="d-flex align-self-center my-auto mx-0"
                >
                    <v-avatar
                        class="ma-1"
                        color="brown"
                        :size="$vuetify.breakpoint.smAndUp ? '30' : '28'"
                    >
                        <span
                            class="white--text 'text-h6"
                            :style="'font-size: 0.8rem !important;'"
                        >
                            {{ getInitial(comment) }}
                        </span>
                    </v-avatar>
                </v-list-item-avatar>
                <div class="d-flex flex-column px-2">
                    <div class="d-flex">
                        <div class="d-flex" style="height: 24px">
                            <p
                                class="d-flex align-self-center font-weight-bold caption ma-0"
                                style="font-size: 0.65rem !important"
                            >
                                {{ comment.user_name }}
                            </p>
                        </div>
                        <div>
                            <v-subheader class="px-2" style="height: 24px">
                                <p
                                    class="d-flex align-self-center font-weight-bold caption ma-0"
                                    style="font-size: 0.5rem !important"
                                >
                                    {{ getDateAndTime(comment) }}
                                </p>
                            </v-subheader>
                        </div>
                    </div>
                    <div>
                        <p
                            class="d-flex align-self-center font-weight-bold caption ma-0"
                            style="
                                color: #424242;
                                font-size: 0.95rem !important;
                            "
                        >
                            {{ comment.text }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="px-1" style="position:absolute; right:8px;" v-show="commentIndex === index">
                <v-list-item-icon
                    class="d-flex align-self-center ma-0"
                >
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
import DeletingConfirmationDialog from "../components/Dialog/DeletingConfirmationDialog.vue";
import Menu from "../components/Buttons/Menu.vue";
export default {
    components: {
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
        hypothesis: {
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
            const hours = dateAndTime.getHours();
            const minutes = dateAndTime.getMinutes();
            return month + "月" + date + "日 " + hours + ":" + minutes;
        },
        selectedMenu(menuTitle, comment){
            if (menuTitle === "削除") {
                this.deletingConfirmationDialog = true;
                this.selectedDeletingComment = comment;
                console.info(this.selectedDeletingComment);
            }
        },
        onClickCancel(){
            this.deletingConfirmationDialog = false;
            this.selectedDeletingHypothesis = { text: null };
        },
        async deleteComment(){
            this.deletingConfirmationDialog = false;
            await this.$store.dispatch(
                "hypothesis/deleteComment", 
                {
                    hypothesis: this.hypothesis,
                    comment: this.selectedDeletingComment,
                }
            );
            this.selectedDeletingHypothesis = { text: null };
        }
    },
};
</script>
