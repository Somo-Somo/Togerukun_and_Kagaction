<template>
    <div>
        <v-row class="d-flex justify-start ma-2 px-2">
            <user-account
                :user="user"
                :userAccountStyle="userAccountStyle"
            ></user-account>
        </v-row>
        <setting-rows
            @onClickLogout="onClickLogout"
            @onClickContact="onClickContact"
        ></setting-rows>
    </div>
</template>

<script>
import UserAccount from "../../../Common/Parts/Molecules/UserAccount.vue";
import SettingRows from "../Parts/SettingRows.vue";

export default {
    components: {
        UserAccount,
        SettingRows,
    },
    data: () => ({}),
    props: {
        user: {
            type: Object,
        },
        userAccountStyle: {
            type: Object,
        },
    },
    computed: {},
    methods: {
        async onClickLogout() {
            this.$emit("onClickLogout");
        },
        onClickContact() {
            this.$emit("onClickContact");
        },
    },
};
</script>
