<template>
    <v-app id="inspire">
        <navigation-bar
            v-if="checkPath && this.$route.path !== '/onboarding'"
        ></navigation-bar>
        <Navbar v-if="checkPath && this.$route.path !== '/onboarding'" />
        <v-main :class="$vuetify.breakpoint.mdAndUp ? '' : 'py-0'">
            <RouterView />
        </v-main>
        <Footer v-if="checkPath" />
    </v-app>
</template>

<script>
import NavigationBar from "./components/Common/Template/NavigationBar.vue";
import Footer from "./components/Footer.vue";
import { NOT_FOUND, INTERNAL_SERVER_ERROR, UNAUTHORIZED } from "./util";
import { mapGetters } from "vuex";

export default {
    components: {
        NavigationBar,
        Footer,
    },
    computed: {
        ...mapGetters({
            check: "auth/check",
            user: "auth/user",
            errorCode: "error/code",
        }),
        checkPath() {
            return this.$route.path !== "/login" ? true : false;
        },
        onboarding() {
            if (this.$store.getters["onboarding/onboarding"]) {
                this.$router.push("/onboarding");
            }
        },
    },
    watch: {
        errorCode(val, old) {
            if (val === UNAUTHORIZED) {
                this.$router.push("/login");
            } else if (val === INTERNAL_SERVER_ERROR) {
                this.$router.push("/500");
            } else if (val === NOT_FOUND) {
                this.$router.push("/not-found");
            }
        },
        $route() {
            this.$store.commit("error/setCode", null);
        },
    },
    created() {
        if (this.check) {
            const data = this.$store.dispatch(
                "initialize/getUserHasProjectAndTodo",
                this.$route
            );
        } else {
            this.$router.push("/login");
        }
    },
};
</script>
