<template>
    <v-app-bar
        class=""
        :style="$vuetify.breakpoint.mdAndUp ? 'height: 64px' : 'height: 56px'"
        color="white"
        elevation="0"
        app
    >
        <v-app-bar-nav-icon
            v-if="
                (!navigation || this.$route.path !== '/onboarding') &&
                $vuetify.breakpoint.md
            "
            @click="clickHumburgerMenu"
            class="mt-3 hidden-sm-and-down"
        ></v-app-bar-nav-icon>
        <v-toolbar-title class="d-flex justify-start mt-3 px-0 ml-md-2">
            <div class="d-flex">
                <header-title
                    v-if="!project"
                    :title="headerTitle"
                ></header-title>
            </div>
            <div class="d-flex">
                <header-title
                    v-if="project"
                    :title="project.name"
                    @onClickHeaderTitle="onClickHeaderTitle('project', project)"
                ></header-title>
            </div>
            <div v-if="todo" class="d-flex">
                <header-title
                    v-if="todo['depth'] > 1"
                    :title="'. . .'"
                    :todo="todo"
                ></header-title>
            </div>
            <div class="d-flex">
                <header-title
                    v-if="parentTodo"
                    :title="parentTodo.name"
                    :todo="todo"
                    @onClickHeaderTitle="onClickHeaderTitle('todo', parentTodo)"
                ></header-title>
            </div>
            <div class="d-flex">
                <header-title
                    v-if="todo"
                    :title="todo.name"
                    :todo="todo"
                    @onClickHeaderTitle="onClickHeaderTitle('todo', todo)"
                ></header-title>
            </div>
        </v-toolbar-title>
    </v-app-bar>
</template>

<script>
import HeaderTitle from "../Molecules/HeaderTitle.vue";

export default {
    components: {
        HeaderTitle,
    },
    props: {
        navigation: {
            type: Boolean,
        },
        headerTitle: {
            type: String,
        },
        project: {
            type: Object,
        },
        todo: {
            type: Object,
        },
        parentTodo: {
            type: Object,
        },
    },
    computed: {
        navigation() {
            return this.$store.getters["navigation/navigation"];
        },
    },
    methods: {
        clickHumburgerMenu() {
            this.$emit("clickHumburgerMenu");
        },
        async onClickHeaderTitle(key, value) {
            if (this.$route.params.id !== value.uuid) {
                if (key === "project") {
                    await this.$store.dispatch("project/selectProject", value);
                    this.$router.push({ path: "/project/" + value.uuid });
                } else if (key === "todo") {
                    await this.$store.dispatch("todo/selectTodo", value);
                    this.$router.push({ path: "/todo/" + value.uuid });
                }
            }
        },
    },
};
</script>
<style scoped lang="sass"></style>
