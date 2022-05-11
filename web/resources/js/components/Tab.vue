<template>
    <div class="px-4 pt-2">
        <div
            class="d-flex justify-space-between"
            :class="$vuetify.breakpoint.mdAndUp ? 'tabsStyle' : 'spTabsStyle'"
        >
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
        </div>
    </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";

export default {
    data: () => ({

    }),
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
            get () {
                return this.tab
            },
            set (newVal) {
                this.$emit('setTab', newVal)
            }
        }
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

<style lang="sass">
.v-slide-group__prev
  display: none !important
.v-slide-group__next
  display: none !important
</style>
