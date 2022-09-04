<template>
    <div class="d-flex">
        <v-list-item-avatar color="brown" :size="userAccountStyle.avatarSize">
            <span
                class="white--text"
                :class="userAccountStyle.avatarInitialFontSizeClass"
                >{{ initial }}</span
            >
        </v-list-item-avatar>
        <v-list-item-content>
            <v-list-item-title :class="userAccountStyle.nameFontSizeClass">{{
                user.name
            }}</v-list-item-title>
            <v-list-item-subtitle :class="userAccountStyle.idColorClass">
                ID: {{ userId }}
            </v-list-item-subtitle>
        </v-list-item-content>
    </div>
</template>

<script>
export default {
    data: () => ({}),
    props: {
        user: {
            type: Object,
        },
        userAccountStyle: {
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
    methods: {},
};
</script>
