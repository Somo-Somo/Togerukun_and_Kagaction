<template>
    <v-btn text>
        {{ btnName }}
    </v-btn>
</template>

<script>
export default {
    props: {
        btnName: {
            type: String,
        },
    },
};
</script>
