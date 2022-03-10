<template>
    <v-container
        class="d-flex flex-column my-6 my-md-2 px-md-16"
        style="max-width: 900px"
        fluid
    >
        <Header :headerTitle="category" />
        <div>
            <v-row class="d-flex justify-start ma-2">
                <div class="ma-2 align-self-center">
                    <v-avatar color="brown" size="64">
                        <span class="white--text text-h5">YS</span>
                    </v-avatar>
                </div>
                <div class="mx-4 my-2">
                    <span v-if="isLogin" class="navbar__item">
                        <p class="ma-0 text-h4">{{ username }}</p>
                        <p class="text-subtitle-2 grey--text ma-0">
                            ID:2fj4oaejdo
                        </p>
                    </span>
                </div>
            </v-row>
            <v-list>
                <v-list-item-group>
                    <v-list-item
                        v-for="(item, i) in items"
                        :key="i"
                        @click.native="onClickItem(item.click)"
                    >
                        <v-list-item-icon>
                            <v-icon v-text="item.icon"></v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title
                                class="font-weight-bold"
                                :class="item.color ? item.color : false"
                                v-text="item.text"
                            ></v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-item-group>
            </v-list>
        </div>
    </v-container>
</template>

<script>
import Header from "../components/Header.vue";

export default {
    components: {
        Header,
    },
    data: () => ({
        category: "ユーザー",
        items: [
            {
                icon: "mdi-help-circle-outline",
                text: "ガイド",
                click: "guide",
            },
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
        apiStatus() {
            return this.$store.getters["auth/apiStatus"];
        },
        isLogin() {
            return this.$store.getters["auth/check"];
        },
        username() {
            return this.$store.getters["auth/username"];
        },
    },
    methods: {
        onClickItem: function (click) {
            if (click === "guide") return;
            if (click === "contact") return;
            if (click === "logout") return this.logout();
            return;
        },
        async logout() {
            await this.$store.dispatch("auth/logout");
            if (this.apiStatus) {
                this.$router.push("/login");
            }
        },
    },
};
</script>
