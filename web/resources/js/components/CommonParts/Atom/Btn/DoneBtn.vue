<template>
    <v-btn color="primary" :disabled="disabled" :loading="loading" text>
        {{ btnName }}
    </v-btn>
</template>

<script>
export default {
    props: {
        btnName: {
            type: String,
        },
        disabled: {
            type: Boolean,
        },
        loading: {
            type: Boolean,
        },
    },
};
</script>
