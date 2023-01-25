<template>
    <v-stepper-header style="width: 100%">
        <template class="" v-for="(stepHeader, stepHeaderIndex) in stepHeaders">
            <v-stepper-step
                class="caption font-weight-bold"
                :key="`${stepHeaderIndex + 1}-title`"
                :complete="stepHeaderIndex + 1 < step"
                :step="stepHeaderIndex + 1"
            >
                {{ stepHeader.title }}
            </v-stepper-step>
            <v-divider
                :key="`${stepHeaderIndex + 1}-divider`"
                v-if="stepHeaderIndex + 1 !== 3"
            ></v-divider>
        </template>
    </v-stepper-header>
</template>

<script>
export default {
    data: () => ({
        stepHeaders: [
            { title: "プロジェクト作成" },
            { title: "ゴール作成" },
            { title: "日付の設定" },
        ],
    }),
    props: {
        step: {
            type: Number,
        },
    },
    computed: {},
    methods: {},
    watch: {},
};
</script>
<style scoped lang="sass">
.v-stepper__header
  box-shadow: none !important
</style>
