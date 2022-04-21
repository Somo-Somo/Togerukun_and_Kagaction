<template>
    <v-menu
        ref="calenderMenu"
        v-model="calenderMenu"
        :close-on-content-click="false"
        :return-value.sync="hypothesis.date"
        transition="scale-transition"
        offset-y
        min-width="auto"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-text-field
                class="d-flex align-self-center ma-0 pt-5"
                v-model="hypothesis.date"
                prepend-icon="mdi-calendar"
                readonly
                v-bind="attrs"
                v-on="on"
            ></v-text-field>
        </template>
        <v-date-picker v-model="date" no-title scrollable>
            <v-spacer></v-spacer>
            <v-btn text color="primary" @click="calenderMenu = false">
                キャンセル
            </v-btn>
            <v-btn text color="primary" @click="$refs.calenderMenu.save(hypothesis.date)">
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
        hypothesis: {
            type: Object,
        },
    },
    computed: {},
    methods: {},
};
</script>
