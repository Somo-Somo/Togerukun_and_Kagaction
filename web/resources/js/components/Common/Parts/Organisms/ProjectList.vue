<template>
    <div class="mt-2">
        <project-list-header
            @onClickCreate="onClickCreate"
        ></project-list-header>
        <v-progress-circular
            class="d-flex mx-auto my-8"
            v-if="loading"
            color="grey darken-1"
            indeterminate
        ></v-progress-circular>
        <v-list
            class="overflow-y-auto py-0"
            style="height: calc(100vh - 304px)"
        >
            <v-list-item
                v-for="(project, index) in projectList"
                :key="index"
                @click="selectProject(project)"
                @mouseover="showMenu = index"
                @mouseout="showMenu = false"
                class="d-flex px-8"
                style="height: 48px"
                link
            >
                <project-list-row
                    :icon="'mdi-folder-outline'"
                    :text="project.name"
                ></project-list-row>
                <v-list-item-icon
                    class="align-self-center mx-0"
                    v-show="showMenu === index"
                >
                    <ellipsis-menu
                        :menus="cardMenus"
                        :selectCard="project"
                        @selectedMenu="selectedMenu"
                    ></ellipsis-menu>
                </v-list-item-icon>
            </v-list-item>
            <v-list-item
                v-if="!loading"
                @click="onClickCreate"
                class="d-flex px-8"
                style="height: 48px"
                link
            >
                <project-list-row
                    :icon="'mdi-plus'"
                    :text="'プロジェクトを追加'"
                ></project-list-row>
            </v-list-item>
        </v-list>
        <!-- 追加のフォーム -->
        <form class="form" @submit.prevent="submitForm()">
            <InputForm
                v-if="inputFormCard"
                @onClickCancel="onClickCancel"
                @submitForm="submitForm"
                :inputForm="inputForm"
                :formCategory="category"
                :formLabel="formLabel"
                :loading="submitLoading"
            />
        </form>
        <DeletingConfirmationDialog
            :deletingConfirmationDialog="deletingConfirmationDialog"
            :selectedDeletingItemName="selectedDeletingProject.name"
            :loading="submitLoading"
            @deleteItem="deleteProject"
            @onClickCancel="onClickCancel"
        />
    </div>
</template>

<script>
import ProjectListHeader from "../Molecules/ProjectListHeader.vue";
import ProjectListRow from "../Molecules/ProjectListRow.vue";
import EllipsisMenu from "../Molecules/EllipsisMenu.vue";
import InputForm from "../components/InputForm.vue";

import DeletingConfirmationDialog from "../components/Dialog/DeletingConfirmationDialog.vue";
import { mapGetters, mapState } from "vuex";

export default {
    components: {
        ProjectListHeader,
        ProjectListRow,
        EllipsisMenu,
        InputForm,
        Menu,
        DeletingConfirmationDialog,
    },
    data: () => ({
        category: "プロジェクト",
        inputFormCard: false,
        deletingConfirmationDialog: false,
        selectedDeletingProject: {
            name: null,
            uuid: null,
        },
        showMenu: false,
        cardMenus: [
            { title: "編集", color: "color: black" },
            { title: "削除", color: "color: red" },
        ],
        submitLoading: false,
    }),
    computed: {
        ...mapState({
            apiStatus: (state) => state.auth.apiStatus,
        }),
        // 後でmapGettersからprops,$emitに移行したい
        ...mapGetters({
            loading: "initialize/loading",
            name: "form/name",
            editObject: "form/editObject",
            inputForm: "form/inputForm",
            submitType: "form/submitType",
            project: "project/project",
            projectList: "project/projectList",
        }),
        formLabel() {
            return this.category + "名を入力";
        },
    },
    methods: {
        onClickCreate() {
            this.$store.dispatch("form/onClickCreate");
            this.inputFormCard = true;
        },
        onClickEdit(value) {
            this.$store.dispatch("form/onClickEdit", value);
            this.inputFormCard = true;
        },
        onClickCancel() {
            this.$store.dispatch("form/closeForm");
            this.inputFormCard = false;
            this.deletingConfirmationDialog = false;
            this.selectedDeletingProject.name = null;
            this.selectedDeletingProject.uuid = null;
        },
        selectProject(project) {
            this.$store.dispatch("project/selectProject", project);
            return this.$router.push({ path: "/project/" + project.uuid });
        },
        selectedMenu(menuTitle, project) {
            if (menuTitle === "編集") {
                this.onClickEdit(project);
            } else if (menuTitle === "削除") {
                this.deletingConfirmationDialog = true;
                this.selectedDeletingProject.name = project.name;
                this.selectedDeletingProject.uuid = project.uuid;
            }
        },
        async deleteProject() {
            this.submitLoading = true;
            await this.$store.dispatch(
                "project/deleteProject",
                this.selectedDeletingProject
            );
            this.submitLoading = false;
            this.deletingConfirmationDialog = false;
            this.selectedDeletingProject.name = null;
            this.selectedDeletingProject.uuid = null;
        },
        async submitForm(form) {
            if (this.submitType === "create") {
                this.submitLoading = true;
                const response = await this.$store.dispatch(
                    "project/createProject",
                    { name: form.text }
                );
                this.submitLoading = false;
                this.$store.dispatch("form/closeForm");
                this.inputFormCard = false;
                this.$router.push("/project/" + response.project.uuid);
            } else if (this.submitType === "edit") {
                // 名前を更新
                this.editObject.name = form.text;
                this.$store.dispatch("project/editProject", this.editObject);
                this.$store.dispatch("form/closeForm");
                this.inputFormCard = false;
            }
        },
    },
    watch: {
        // ダイアログが閉じた後フォームの値を全て空にする * computedに移行したい
        inputForm(inputForm) {
            if (!inputForm) {
                this.inputFormCard = false;
                this.deletingConfirmationDialog = false;
                this.selectedDeletingProject.name = null;
                this.selectedDeletingProject.uuid = null;
            }
        },
    },
};
</script>
