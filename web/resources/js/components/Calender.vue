<template>
    <v-menu
        ref="calenderMenu"
        v-model="calenderMenu"
        :close-on-content-click="false"
        :return-value.sync="date"
        transition="scale-transition"
        offset-y
        min-width="auto"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-text-field
                v-model="date"
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
            <v-btn text color="primary" @click="$refs.calenderMenu.save(date)">
                保存
            </v-btn>
        </v-date-picker>
    </v-menu>
</template>

<script>
export default {
    data: () => ({
        calenderMenu: false,
        date: null,
    }),
    props: {

    },
    computed: {
        date() {
            return this.date
                ? this.date
                : new Date(Date.now() - new Date().getTimezoneOffset() * 60000)
                      .toISOString()
                      .substr(0, 10);
        },
    },
    methods: {},
};
</script>
