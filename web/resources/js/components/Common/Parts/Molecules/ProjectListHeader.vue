<template>
    <div class="d-flex justify-space-between px-2">
        <v-subheader
            class="d-flex align-self-center"
            style="font-size: 0.825em; height: 32px"
            >プロジェクト
        </v-subheader>
        <v-icon
            class="hidden-sm-and-down pr-2"
            size="18"
            height="18"
            @click="onClickCreate"
            >mdi-plus-circle</v-icon
        >
    </div>
</template>

<script>
export default {
    data: () => ({}),
    computed: {},
    methods: {
        onClickCreate() {
            this.$emit("onClickCreate");
        },
    },
};
</script>
