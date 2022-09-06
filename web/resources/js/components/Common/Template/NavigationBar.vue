<template>
    <div class="hidden-sm-and-down" style="width: 256px">
        <v-navigation-drawer
            color="#80CBC4"
            v-model="navigation"
            app
            hide-overlay
        >
            <navigation-header
                :transparent="transparent"
                @onClickChevronDoubleLeft="switchNavigation"
            ></navigation-header>
            <v-list class="pb-4" style="padding-top: 72px">
                <v-list-item
                    v-for="item in items"
                    :key="item.icon"
                    @click="fromItem(item)"
                    class="d-flex px-8"
                    style="height: 48px"
                    link
                >
                    <v-list-item-icon class="align-self-center mr-6">
                        <v-icon color="teal lighten-5">{{ item.icon }}</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content class="align-self-center">
                        <v-list-item-title>{{ item.text }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
            <v-divider class="mx-6" color="#80CBC4"></v-divider>
            <project-list></project-list>
            <navigation-footer :user="user"></navigation-footer>
        </v-navigation-drawer>
    </div>
</template>

<script>
import NavigationHeader from "../Parts/Organisms/NavigationHeader.vue";
import ProjectList from "../Parts/Organisms/ProjectList.vue";
import NavigationFooter from "../Parts/Organisms/NavigationFooter.vue";
import { mapGetters } from "vuex";

export default {
    components: {
        NavigationHeader,
        ProjectList,
        NavigationFooter,
    },
    data: () => ({
        items: [
            { icon: "mdi-calendar-text", text: "予定", url: "/schedule" },
            { icon: "mdi-cog", text: "設定", url: "/setting" },
        ],
        transparent: "rgba(128, 128, 128, 0.3)",
    }),
    props: {
        navigation: {
            type: Boolean,
        },
    },
    computed: {
        ...mapGetters({
            loading: "initialize/loading",
            user: "auth/user",
            projectList: "project/projectList",
        }),
    },
    methods: {
        switchNavigation() {
            this.$emit("switchNavigation");
        },
        async fromItem(item) {
            return this.$router.push({ path: item.url });
        },
        selectProject(project) {
            if (this.$route.params.id !== project.uuid) {
                this.$store.dispatch("project/selectProject", project);
                return this.$router.push({ path: "/project/" + project.uuid });
            }
        },
        onClickCreate() {
            this.$store.dispatch("form/onClickCreate");
        },
    },
};
</script>

<style scoped lang="sass">
.show-btn
  color: rgba(128, 128, 128, 1) !important
</style>
