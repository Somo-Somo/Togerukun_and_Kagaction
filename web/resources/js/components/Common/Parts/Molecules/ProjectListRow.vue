<template>
    <div>
        <v-list-item-icon class="align-self-center mr-6">
            <v-icon color="teal lighten-5">icon</v-icon>
        </v-list-item-icon>
        <v-list-item-content class="align-self-center">
            <v-list-item-title style="font-size: 0.9em">{{
                text
            }}</v-list-item-title>
        </v-list-item-content>
    </div>
</template>

<script>
export default {
    data: () => ({}),
    props: {
        icon: {
            type: String,
        },
        text: {
            type: String,
        },
    },
    computed: {},
    methods: {},
};
</script>
