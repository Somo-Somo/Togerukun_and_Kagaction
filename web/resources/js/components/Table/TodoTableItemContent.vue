<template>
    <div>
        <v-list-item-subtitle class="d-flex align-content-start mt-3 mb-1">
            <DateSubTitle :todo="todo"/>
            <div class="d-flex" style="max-width: 66%">
                <p
                    class="ma-0 grey--text font-weight-bold align-self-center"
                    style="
                        font-size: 8px;
                        max-width: 100%;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    "
                >
                    {{ parentName(todo) }}
                </p>
                <p
                    class="ma-0 grey--text font-weight-bold align-self-center"
                    style="font-size: 8px"
                >
                    {{ parentType(todo) }}
                </p>
            </div>
        </v-list-item-subtitle>
        <v-list-item-title class="py-2 pb-4">
            <p
                class="font-weight-black ma-0"
                style="
                    max-width: calc(100% - 36px);
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                "
                :style="
                    $vuetify.breakpoint.smAndUp
                        ? 'font-size:1rem'
                        : 'font-size:0.8rem'
                "
            >
                {{ todo.name }}
            </p></v-list-item-title
        >
    </div>
</template>

<script>
import DateSubTitle from "../Date/DateSubTitle.vue";
export default {
    components: {
        DateSubTitle,
    },
    data: () => ({
        cardMenu: [{ title: "削除", color: "color: red" }],
    }),
    props: {
        project: {
            type: Object,
        },
        todo: {
            type: Object,
        },
        todoList: {
            type: Array,
        },
    },
    computed: {
        parentName() {
            return (todo) => {
                if (todo.depth === 0) {
                    return "「" + this.project.name;
                } else if (todo.depth > 0) {
                    let parentName;
                    this.todoList.map((value) => {
                        if (todo.parentUuid === value.uuid)
                            parentName = value.name;
                    });
                    return "「" + parentName;
                }
            };
        },
        parentType() {
            return (todo) => {
                if (todo.depth === 0) return "」のゴール";
                if (todo.depth > 0) return "」のためのToDo";
            };
        },
    },
    methods: {},
};
</script>
