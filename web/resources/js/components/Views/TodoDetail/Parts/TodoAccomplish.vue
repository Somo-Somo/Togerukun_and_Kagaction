<template>
    <div
        class="py-2 d-flex justify-start"
        :style="$vuetify.breakpoint.mdAndUp ? 'height: 72px' : 'height: 48px'"
    >
        <v-subheader
            class="d-flex align-self-center pa-md-0"
            :class="
                $vuetify.breakpoint.mdAndUp ? 'todoSubTitle' : 'spTodoSubTitle'
            "
        >
            <p
                class="ma-0 font-weight-bold"
                style="min-width: 36px"
                color="grey darken-1"
            >
                達成：
            </p>
        </v-subheader>
        <v-col class="px-4 py-0 d-flex align-self-center">
            <v-checkbox
                v-model="todo.accomplish"
                @click="onClickAccomplish(todo)"
            ></v-checkbox>
        </v-col>
    </div>
</template>

<script>
export default {
    components: {},
    data: () => ({}),
    props: {
        todo: {
            type: Object,
        },
    },
    computed: {},
    methods: {
        onClickAccomplish(todo) {
            this.$emit("onClickAccomplish", todo);
        },
    },
};
</script>

<style scoped lang="sass"></style>
