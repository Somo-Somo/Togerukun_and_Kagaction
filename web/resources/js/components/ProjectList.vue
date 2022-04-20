<template>
  <div class="mt-4">
    <div class="d-flex justify-space-between px-2">
        <v-subheader class="d-flex align-self-center" style="font-size: 0.825em; height: 32px;"
            >プロジェクト
        </v-subheader>
        <v-icon
            class="hidden-sm-and-down pr-2"
            size="18"
            height="18"
            @click="onClickCreate"
            >mdi-plus-circle</v-icon
        >
    </div>
    <v-progress-circular
        class="d-flex mx-auto my-8"
        v-if="loading"
        color="grey darken-1"
        indeterminate
    ></v-progress-circular>
    <v-list class="overflow-y-auto py-0" height="calc(100% - 304px)">
        <v-list-item
            v-for="project in projectList"
            :key="project.uuid"
            @click="selectProject(project)"
            class="d-flex px-8"
            style="height: 48px"
            link
        >
            <v-list-item-icon class="align-self-center mr-6">
                <v-icon color="teal lighten-5">mdi-folder-outline</v-icon>
            </v-list-item-icon>
            <v-list-item-content class="align-self-center">
                <v-list-item-title>{{ project.name }}</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-list-item @click="onClickCreate" class="d-flex px-8" style="height: 48px" link>
            <v-list-item-icon class="align-self-center mr-6">
                <v-icon color="teal lighten-5">mdi-plus</v-icon>
            </v-list-item-icon>
            <v-list-item-content class="align-self-center">
                <v-list-item-title style="font-size: 0.9em;">プロジェクト追加</v-list-item-title>
            </v-list-item-content>
        </v-list-item>
    </v-list>
    <!-- 追加のフォーム -->
    <form class="form" @submit.prevent="submitForm()">
        <InputForm
            v-if="form"
            @onClickCancel="onClickCancel"
            @submitForm="submitForm"
            :inputForm="inputForm"
            :category="category"
            :loading="submitLoading"
        />
    </form>
  </div>
</template>

<script>
import InputForm from "../components/InputForm.vue";
import { mapGetters, mapState } from "vuex";

export default {
    components: {
        InputForm,
    },
    data: () => ({
        category: "プロジェクト",
        submitLoading: false,
        form: false,
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
    },
    methods: {
        onClickCreate() {
            this.$store.dispatch("form/onClickCreate");
            this.form = true;
        },
        onClickEdit(value) {
            this.$store.dispatch("form/onClickEdit", value);
        },
        onClickCancel() {
            this.$store.dispatch("form/closeForm");
            this.form = false;
        },
        selectProject(project) {
            this.$store.dispatch("project/selectProject", project);
            return this.$router.push({ path: "/project/" + project.uuid });
        },
        async submitForm() {
            if (this.submitType === "create") {
                this.submitLoading = true;
                const response = await this.$store.dispatch(
                    "project/createProject",
                    { name: this.name }
                );
                this.submitLoading = false;
                this.$store.dispatch("form/closeForm");
                this.form = false;
                this.$router.push("/project/" + response.project.uuid);
            } else if (this.submitType === "edit") {
                // 名前を更新
                this.editObject.name = this.name;
                this.$store.dispatch("project/editProject", this.editObject);
                this.$store.dispatch("form/closeForm");
                this.form = false;
            }
        },
    },
    watch: {
        // ダイアログが閉じた後フォームの値を全て空にする * computedに移行したい
        inputForm(inputForm) {
        if (!inputForm) {
            this.form = false;
        }
        },
    },
};
</script>
