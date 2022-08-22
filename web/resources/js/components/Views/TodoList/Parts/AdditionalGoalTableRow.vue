<template>
    <v-list-item
        class="d-flex justify-space-around px-3"
        style="height: 64px"
        link
    >
        <v-icon class="pa-1">mdi-plus</v-icon>
        <v-list-item-content class="px-2">
            <v-list-item-title>
                <p
                    class="grey--text font-weight-bold ma-0 pa-1"
                    :style="
                        $vuetify.breakpoint.smAndUp
                            ? 'font-size:0.9rem'
                            : 'font-size:0.75rem'
                    "
                >
                    ゴールを追加する
                </p>
            </v-list-item-title>
        </v-list-item-content>
    </v-list-item>
</template>

<script>
export default {
    components: {},
    data: () => ({}),
    props: {},
    computed: {},
    methods: {},
};
</script>
