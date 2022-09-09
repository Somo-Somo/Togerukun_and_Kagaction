<template>
    <div class="d-flex" style="width: 100%">
        <v-list-item-icon class="align-self-center mr-6">
            <v-icon color="teal lighten-5">{{ icon }}</v-icon>
        </v-list-item-icon>
        <v-list-item-content class="align-self-center">
            <v-list-item-title style="font-size: 0.9em">{{
                text
            }}</v-list-item-title>
        </v-list-item-content>
        <v-list-item-icon
            class="align-self-center mx-0"
            v-show="showMenu === projectListRowIndex"
        >
            <ellipsis-menu
                :menus="cardMenus"
                :selectCard="project"
                @selectedMenu="selectedMenu"
            ></ellipsis-menu>
        </v-list-item-icon>
    </div>
</template>

<script>
import EllipsisMenu from "../Molecules/EllipsisMenu.vue";
export default {
    components: {
        EllipsisMenu,
    },
    data: () => ({
        cardMenus: [
            { title: "編集", color: "color: black" },
            { title: "削除", color: "color: red" },
        ],
    }),
    props: {
        icon: {
            type: String,
        },
        text: {
            type: String,
        },
        showMenu: {
            type: [Boolean, String],
        },
        projectListRowIndex: {
            type: [Boolean, Number, String],
        },
        project: { type: Object },
    },
    computed: {},
    methods: {
        selectedMenu(menuTitle, project) {
            this.$emit("selectedMenu", menuTitle, project);
        },
    },
};
</script>
