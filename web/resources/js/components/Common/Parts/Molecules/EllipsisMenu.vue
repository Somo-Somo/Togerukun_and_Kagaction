<template>
    <v-menu class="rounded-lg elevation-0" offset-y>
        <template v-slot:activator="{ on, attrs }">
            <v-list-item-action class="ma-0">
                <v-btn v-bind="attrs" v-on="on" small icon link>
                    <v-icon :size="$vuetify.breakpoint.smAndUp ? '24' : '20'">
                        mdi-dots-vertical
                    </v-icon>
                </v-btn>
            </v-list-item-action>
        </template>
        <v-list>
            <v-list-item
                v-for="menu in menus"
                :key="menu.title"
                @click="selectedMenu(menu.title)"
                link
            >
                <v-list-item-title :style="menu.color">{{
                    menu.title
                }}</v-list-item-title>
            </v-list-item>
        </v-list>
    </v-menu>
</template>

<script>
export default {
    data: () => ({}),
    props: {
        menus: {
            type: Array,
        },
        selectCard: {
            type: Object,
        },
    },
    methods: {
        selectedMenu(menuTitle) {
            this.$emit("selectedMenu", menuTitle, this.selectCard);
        },
    },
};
</script>
