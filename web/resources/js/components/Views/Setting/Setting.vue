<template>
    <v-container
        class="d-flex flex-column my-6 my-md-2 px-md-16"
        style="max-width: 900px"
        fluid
    >
        <Header :headerTitle="'設定'" v-if="$vuetify.breakpoint.mdAndUp" />
        <setting-main
            :apiStatus="apiStatus"
            :isLogin="isLogin"
            :user="user"
            :userAccountStyle="userAccountStyle"
            @onClickLogout="onClickLogout"
            @onClickContact="onClickContact"
        ></setting-main>
    </v-container>
</template>

<script>
import Header from "../../Common/Parts/Organisms/TodoHeader.vue";
import SettingMain from "./Templates/SettingMain.vue";
import { mapGetters } from "vuex";

export default {
    components: {
        Header,
        SettingMain,
    },
    data: () => ({
        userAccountStyle: {
            avatarSize: 48,
            avatarInitialFontSizeClass: "text-subtitle-1",
            nameFontSizeClass: "text-h5",
            idFontSizeClass: "",
            idColorClass: "grey--text",
        },
        items: [
            {
                icon: "mdi-email-fast-outline ",
                text: "お問い合わせ",
                click: "contact",
            },
            {
                icon: "mdi-logout",
                text: "ログアウト",
                color: "error--text",
                click: "logout",
            },
        ],
    }),
    computed: {
        ...mapGetters({
            apiStatus: "auth/apiStatus",
            isLogin: "auth/check",
            user: "auth/user",
        }),
    },
    methods: {
        async onClickLogout() {
            await this.$store.dispatch("auth/logout");
            if (this.apiStatus) {
                this.$router.go({ path: "/login", force: true });
            }
        },
        onClickContact() {
            window.open("https://forms.gle/uzKkE8FGqThyWWgD8", "_blank");
        },
    },
};
</script>
