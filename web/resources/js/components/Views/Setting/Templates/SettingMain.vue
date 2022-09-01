<template>
    <div>
        <v-row class="d-flex justify-start ma-2">
            <user-account :user="user"></user-account>
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
                            :class="item.color ? item.color : false"
                            :style="
                                $vuetify.breakpoint.smAndUp
                                    ? 'font-size: 1rem;'
                                    : 'font-size: 0.9rem;'
                            "
                            v-text="item.text"
                        ></v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list-item-group>
        </v-list>
    </div>
</template>

<script>
import UserAccount from "../../../Common/Parts/Molecules/UserAccount.vue";

export default {
    components: {
        Header,
        UserAccount,
    },
    data: () => ({}),
    props: {
        apiStatus: {
            type: Boolean,
        },
        isLogin: {
            type: Boolean,
        },
        user: {
            type: Object,
        },
    },
    computed: {
        initial() {
            return this.user.name.charAt(0);
        },
        userId() {
            return this.user.uuid.substr(0, 23);
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
            this.$emit("onClickLogout");
        },
        contact() {
            this.$emit("onClickContact");
        },
    },
};
</script>
