<template>
    <div
        class="py-2 ml-md-2 d-flex align-self-center"
        :style="$vuetify.breakpoint.mdAndUp ? 'height: 72px' : 'height: 48px'"
    >
        <v-subheader class="d-flex align-self-center pa-md-0">
            <p class="ma-0 font-weight-bold" color="grey darken-1">日付 :</p>
        </v-subheader>
        <v-col class="px-md-4 pa-0 d-flex align-self-center">
            <calender-form
                :date="todo.date"
                :dateLabel="null"
                @onClickSave="onClickSave"
                @onClickRemove="onClickRemove"
            ></calender-form>
        </v-col>
    </div>
</template>

<script>
import CalenderForm from "../../../Common/Parts/Molecules/CalenderForm.vue";

export default {
    components: {
        CalenderForm,
    },
    data: () => ({}),
    props: {
        todo: {
            type: Object,
        },
    },
    computed: {},
    methods: {
        onClickSave(date) {
            this.$emit("onClickSave", date);
        },
        onClickRemove() {
            this.$emit("onClickRemove");
        },
    },
};
</script>

<style scoped lang="sass"></style>
