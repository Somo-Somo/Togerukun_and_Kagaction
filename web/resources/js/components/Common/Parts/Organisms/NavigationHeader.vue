<template>
    <v-app-bar
        class="d-flex px-0 py-0 mt-2"
        color="#80CBC4"
        style="height: 72px"
        elevation="0"
        absolute
    >
        <v-hover v-slot="{ hover }">
            <div class="d-flex justify-space-between" style="width: 222px">
                <img :src="'/img/Kagaction_logo_app.png'" alt="アプリロゴ" />
                <v-icon
                    class="my-6"
                    style="height: 24px"
                    :class="{ 'show-btn': hover }"
                    :color="transparent"
                    @click="onClickChevronDoubleLeft"
                    >mdi-chevron-double-left</v-icon
                >
            </div>
        </v-hover>
    </v-app-bar>
</template>

<script>
export default {
    data: () => ({}),
    props: {
        transparent: {
            type: String,
        },
    },
    computed: {},
    methods: {
        onClickChevronDoubleLeft() {
            this.$emit("onClickChevronDoubleLeft");
        },
    },
};
</script>

<style scoped lang="sass">
.show-btn
  color: rgba(128, 128, 128, 1) !important
</style>
