<template>
    <div>
        <v-list-item-avatar color="brown" size="36">
            <span class="white--text text-subtitle-2">{{ initial }}</span>
        </v-list-item-avatar>
        <v-list-item-content>
            <v-list-item-title>{{ user.name }}</v-list-item-title>
            <v-list-item-subtitle style="font-size: 0.75em">
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
