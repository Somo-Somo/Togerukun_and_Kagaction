<template>
    <div>
        <v-list-item-action class="px-4 ma-auto" style="height: 24px">
            <v-icon>mdi-help-circle-outline</v-icon>
        </v-list-item-action>
        <div class="d-flex px-2">
            <v-list-item-title class="py-auto">
                <p
                    class="font-weight-black ma-0"
                    style="
                        font-size: 1rem;
                        max-width: calc(100% - 36px);
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    "
                >
                    {{ cause.text }}
                </p></v-list-item-title
            >
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    data: () => ({}),
    props: {
        cause: {
            type: Object,
        },
    },
    computed: {},
    methods: {},
};
</script>
<style scoped lang="sass">
.v-icon.v-icon:after
  background-color: transparent !important
</style>
