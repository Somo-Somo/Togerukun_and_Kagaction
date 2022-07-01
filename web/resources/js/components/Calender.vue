<template>
    <v-menu
        ref="calenderMenu"
        v-model="calenderMenu"
        :close-on-content-click="false"
        transition="scale-transition"
        offset-y
        min-width="auto"
    >
        <template class="d-flex" v-slot:activator="{ on, attrs }">
            <v-text-field
                class="d-flex align-self-center ma-0 pt-5"
                v-model="todo.date"
                :prepend-icon="
                    $vuetify.breakpoint.smAndUp ? 'mdi-calendar' : null
                "
                readonly
                v-bind="attrs"
                v-on="on"
            >
            </v-text-field>
            <v-btn
                class="d-flex align-self-center"
                style="position: relative; left: -20px"
                @click="onClickRemove(todo.date)"
                small
                icon
            >
                <v-icon :size="$vuetify.breakpoint.smAndUp ? '20' : '14'"
                    >mdi-close</v-icon
                >
            </v-btn>
        </template>
        <v-date-picker v-model="todo.date" no-title scrollable>
            <v-spacer></v-spacer>
            <v-btn text color="primary" @click="calenderMenu = false">
                キャンセル
            </v-btn>
            <v-btn text color="primary" @click="onClickSave(todo.date)">
                保存
            </v-btn>
        </v-date-picker>
    </v-menu>
</template>

<script>
export default {
    data: () => ({
        calenderMenu: false,
    }),
    props: {
        todo: {
            type: Object,
        },
    },
    computed: {},
    methods: {
        onClickSave(date) {
            this.calenderMenu = false;
            this.$emit("onClickSave", date);
        },
        onClickRemove(date) {
            if (date) this.$emit("onClickRemove");
        },
    },
};
</script>
