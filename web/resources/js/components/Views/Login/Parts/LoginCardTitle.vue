<template>
    <v-card-title class="d-flex text-center pa-4">
        <div class="mx-auto">
            <img src="/img/Kagaction_Login.svg" />
        </div>
        <div class="mx-auto my-0">
            <p class="ma-0 fill-width font-weight-bold" style="font-size: 14px">
                {{ title }}
            </p>
        </div>
    </v-card-title>
</template>

<script>
export default {
    data: () => ({}),
    props: {
        title: {
            type: String,
        },
    },
};
</script>
