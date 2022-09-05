<template>
    <v-footer color="#80CBC4" class="py-0" absolute>
        <v-divider color="#80CBC4"></v-divider>
        <v-sheet
            class="d-flex flex-row justify-space-around my-auto"
            style="height: 72px"
            color="#80CBC4"
        >
            <user-account
                :user="user"
                :userAccountStyle="userAccountStyle"
            ></user-account>
        </v-sheet>
    </v-footer>
</template>

<script>
import UserAccount from "../Molecules/UserAccount.vue";

export default {
    components: {
        UserAccount,
    },
    data: () => ({
        userAccountStyle: {
            avatarSize: 36,
            avatarInitialFontSizeClass: "text-subtitle-2",
            nameFontSizeClass: "text-subtitle-1",
            idFontSizeClass: "text-caption",
            idColorClass: "",
        },
    }),
    props: {
        user: {
            type: String,
        },
    },
    computed: {},
    methods: {},
};
</script>

<style scoped lang="sass"></style>
