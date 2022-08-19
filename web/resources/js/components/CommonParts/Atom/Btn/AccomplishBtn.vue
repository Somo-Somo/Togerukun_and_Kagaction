<template>
    <v-btn icon :height="height" :width="width" :color="color">
        <v-icon>mdi-checkbox-marked-circle-outline</v-icon>
    </v-btn>
</template>

<script>
export default {
    props: {
        height: {
            type: Number,
        },
        width: {
            type: Number,
        },
        color: {
            type: String,
        },
    },
};
</script>
