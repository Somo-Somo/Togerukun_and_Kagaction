<template>
    <v-alert class="my-2" outlined type="success" text>
        {{ message }}
    </v-alert>
</template>

<script>
export default {
    data: () => ({}),
    props: {
        message: {
            type: String,
        },
    },
};
</script>
