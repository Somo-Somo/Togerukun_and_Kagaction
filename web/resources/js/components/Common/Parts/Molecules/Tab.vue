<template>
    <v-tabs
        v-model="headerTab"
        class="px-md-0"
        color="black"
        :height="$vuetify.breakpoint.mdAndUp ? '' : '40'"
    >
        <v-tabs-slider color="#80CBC4"></v-tabs-slider>
        <v-tab
            class="px-0"
            v-for="todoStatus in todoStatuses"
            :key="todoStatus.name"
            :class="$vuetify.breakpoint.mdAndUp ? '' : 'spTabStyle'"
        >
            <p class="ma-0 font-weight-bold">
                {{ todoStatus.name }}
            </p>
        </v-tab>
    </v-tabs>
</template>

<script>
export default {
    data: () => ({}),
    props: {
        tab: {
            type: Number,
        },
        todoStatuses: {
            type: Array,
        },
    },
    computed: {
        headerTab: {
            get() {
                return this.tab;
            },
            set(newVal) {
                this.$emit("setTab", newVal);
            },
        },
    },
    methods: {},
};
</script>

<style scoped lang="sass">
.tabsStyle
  width: 772px

.spTabsStyle
  width: 100%
  height: 40px
  position: absolute
  top: 64px

.spTabStyle
  width: 25%
  height: 40px
  font-size: 0.75rem
</style>
