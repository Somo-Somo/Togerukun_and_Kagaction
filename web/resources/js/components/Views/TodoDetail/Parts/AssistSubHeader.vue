<template>
    <v-subheader
        v-if="tab !== 2"
        class="px-md-0 mt-2 todoSubTitle"
        v-show="$vuetify.breakpoint.smAndUp"
    >
        <p
            class="ma-0 font-weight-black caption align-self-center"
            color="grey lighten-1"
            style="
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            "
            :style="
                $vuetify.breakpoint.smAndUp
                    ? 'max-width: 70%'
                    : 'max-width: 40vw'
            "
        >
            「{{ todo.name }}
        </p>
        <p
            class="ma-0 font-weight-black caption align-self-center"
            color="grey lighten-1"
        >
            {{ assistSubHeaderText(tab) }}
        </p>
    </v-subheader>
</template>

<script>
export default {
    components: {},
    data: () => ({}),
    props: {
        tab: {
            type: Number,
        },
        todo: {
            type: Object,
        },
    },
    computed: {
        assistSubHeaderText() {
            return (tab) => {
                if (tab === 0) {
                    return "」を達成するには？";
                } else if (tab === 1) {
                    return "」が達成できない原因は？";
                }
            };
        },
    },
    methods: {},
    watch: {},
};
</script>

<style scoped lang="sass">

.todoSubTitle
  font-size: 1rem
  height: 36px

.spTodoSubTitle
  font-size: 12px
  height: 24px
  padding: 0 0 0 12px
</style>
