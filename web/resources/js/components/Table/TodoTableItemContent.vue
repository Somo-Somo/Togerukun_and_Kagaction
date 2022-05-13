<template>
    <div>
        <v-list-item-subtitle class="d-flex align-content-start mt-3 mb-1">
            <div
                class="d-flex"
                :style="subTitle(todo).backgroundColor"
                v-if="subTitle(todo)"
            >
                <v-icon
                    :size="subTitle(todo).iconSize"
                    :color="subTitle(todo).iconColor"
                    >{{ subTitle(todo).icon }}</v-icon
                >
                <p
                    class="ma-0 px-2 font-weight-bold align-self-center"
                    :class="subTitle(todo).fontColor"
                    :style="
                        $vuetify.breakpoint.smAndUp
                            ? 'font-size:12px'
                            : 'font-size:8px'
                    "
                >
                    {{ subTitle(todo).title }}
                </p>
            </div>
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
export default {
    data: () => ({
        todoStatus: {
            date: {
                icon: "mdi-clock-outline",
                iconSize: 14,
                iconColor: "#212121",
                fontColor: "#212121--text",
                backgroundColor: "background-color: transparent",
            },
        },
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
        subTitle() {
            return (todo) => {
                return todo.date ? this.calcDate(todo) : false;
            };
        },
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
    methods: {
        calcDate(todo) {
            const today = new Date(
                Date.now() - new Date().getTimezoneOffset() * 60000
            )
                .toISOString()
                .substr(0, 10);
            const diff =
                (new Date(todo.date) - new Date(today)) / (60 * 60 * 1000 * 24);

            if (diff > 0) {
                this.todoStatus.date.title = "残り" + diff + "日";
                this.todoStatus.date.backgroundColor =
                    diff < 4 ? "background-color: yellow" : null;
            } else if (diff === 0) {
                this.todoStatus.date.title = "今日";
                this.todoStatus.date.backgroundColor =
                    "background-color: skyblue";
            } else if (todo.accomplish) {
                const year = new Date(todo.date).getFullYear();
                const month = new Date(todo.date).getMonth() + 1;
                const day = new Date(todo.date).getDay() + 1;
                this.todoStatus.date.title  =
                    new Date().getFullYear() === year
                        ? month + "月" + day + "日"
                        : year + "年" + month + "月" + day + "日";
                this.todoStatus.date.backgroundColor =
                    "background-color: transparent;";
            } else {
                this.todoStatus.date.title = Math.abs(diff) + "日経過";
                this.todoStatus.date.backgroundColor =
                    "background-color: coral";
            }
            return this.todoStatus.date;
        },
    },
};
</script>
