<template>
    <v-container
        class="d-flex flex-column my-6 my-md-2 px-md-16"
        style="max-width: 900px"
        fluid
    >
        <Header :headerTitle="'設定'" v-if="$vuetify.breakpoint.mdAndUp" />
        <div>
            <v-row class="d-flex justify-start ma-2">
                <div class="ma-2 align-self-center">
                    <v-avatar color="brown" :size="$vuetify.breakpoint.smAndUp ? '64' : '48'">
                        <span class="white--text" :class="$vuetify.breakpoint.smAndUp ? 'text-h5' : 'text-h6'">
                            {{ initial }}
                        </span>
                    </v-avatar>
                </div>
                <div class="mx-4 my-auto">
                    <span v-if="isLogin" class="navbar__item">
                        <p 
                         class="ma-0 text-h4"
                         :class="$vuetify.breakpoint.smAndUp ? 'text-h4' : 'text-h6'"
                        >{{ user.name }}</p>
                        <p class="text-subtitle-2 grey--text ma-0">
                            ID:{{ userId }}
                        </p>
                    </span>
                </div>
            </v-row>
            <v-list class="pa-0 ma-2">
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
                                :class="item.color ? item.color : false "
                                :style="$vuetify.breakpoint.smAndUp ? 'font-size: 1rem;' : 'font-size: 0.9rem;'"
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
import { mapGetters } from "vuex";

export default {
    components: {
        Header,
    },
    data: () => ({
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
        initial() {
          return this.user.name.charAt(0);
        },
        userId() {
            return this.user.uuid.substr(0,23);
        },
    },
    methods: {
        onClickItem: function (click) {
            if (click === "guide") return;
            if (click === "contact") return this.contact();
            if (click === "logout") return this.logout();
            return;
        },
        async logout() {
            await this.$store.dispatch("auth/logout");
            if (this.apiStatus) {
                this.$router.go({path: '/login', force: true})
            }
        },
        contact() {
            window.open('https://forms.gle/uzKkE8FGqThyWWgD8', '_blank');
        }
    },
};
</script>
