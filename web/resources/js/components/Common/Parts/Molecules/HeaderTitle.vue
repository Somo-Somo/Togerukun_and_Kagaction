<template>
    <div class="d-flex align-self-center">
        <h1
            v-if="todo"
            :style="
                $vuetify.breakpoint.mdAndUp
                    ? 'font-size: 18px'
                    : 'font-size: 16px'
            "
            class="d-flex align-self-center"
        >
            /
        </h1>
        <v-btn class="px-2" @click="onClickHeaderTitle('todo', todo)" text>
            <h1
                class="omitHeaderTitle"
                :style="
                    $vuetify.breakpoint.mdAndUp
                        ? 'font-size: 18px'
                        : 'font-size: 16px'
                "
            >
                {{ title }}
            </h1>
        </v-btn>
    </div>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
        },
        project: {
            type: Object,
        },
        todo: {
            type: Object,
        },
    },
    computed: {},
    methods: {
        onClickHeaderTitle() {
            this.$emit("onClickHeaderTitle");
        },
    },
};
</script>
<style scoped lang="sass">
.omitHeaderTitle
  max-width:30vw
  white-space: nowrap
  overflow: hidden
  text-overflow: ellipsis
</style>
