<template>
    <v-container
        class="d-flex flex-column py-6 my-md-2 px-md-16"
        style="max-width: 900px"
        fluid
    >
        <Header :headerTitle="'予定'" />
        <template>
            <div
                class="d-flex justify-space-between"
                :class="
                    $vuetify.breakpoint.mdAndUp ? 'tabsStyle' : 'spTabsStyle'
                "
            >
                <v-tabs
                    v-model="tab"
                    class="px-md-0"
                    color="black"
                    :height="$vuetify.breakpoint.mdAndUp ? '' : '40'"
                >
                    <v-tabs-slider color="#80CBC4"></v-tabs-slider>
                    <v-tab
                        class="px-0"
                        v-for="period in periods"
                        :key="period.name"
                        :class="$vuetify.breakpoint.mdAndUp ? '' : 'spTabStyle'"
                    >
                        <p class="ma-0 font-weight-bold">{{ period.name }}</p>
                    </v-tab>
                </v-tabs>
            </div>
            <v-divider
                v-if="!$vuetify.breakpoint.mdAndUp"
                style="position: relative; top: 92px"
            ></v-divider>
            <div
                class="overflow-y-auto d-flex flex-column"
                :class="
                    $vuetify.breakpoint.mdAndUp ? 'cardStyle' : 'spCardStyle'
                "
            >
                <v-progress-circular
                    class="mx-auto my-8"
                    v-if="loading"
                    color="grey lighten-1"
                    indeterminate
                ></v-progress-circular>

                <ScheduleCards
                    :projectList="projectList"
                    :todoList="scheduleLists"
                    :scheduleList="scheduleList"
                    :period="periods[tab]"
                    :loading="loading"
                />
            </div>
        </template>
    </v-container>
</template>

<script>
import Header from "../components/Header.vue";
import ScheduleCards from "../components/Cards/ScheduleCard.vue";
import { mapGetters } from "vuex";

export default {
    components: {
        Header,
        ScheduleCards,
    },
    data: () => ({
        category: "ToDo",
        tab: null,
        periods: [
            { name: "今日", show: false },
            { name: "7日以内", show: false },
            { name: "全期間", show: false },
            { name: "期限切れ", show: false },
        ],
    }),
    computed: {
        ...mapGetters({
            loading: "initialize/loading",
            projectList: "project/projectList",
            todoList: "todo/todoList",
            allTodoList: "todo/allTodoList",
        }),
        scheduleList() {
            return this.convertSchduleList();
        },
    },
    methods: {
        convertSchduleList() {
            const allTodo = this.expandProjectList();
            const todoListWithDates = this.checkIfThereIsADate(allTodo);
            const scheduleList = this.sortTodoListByDate(todoListWithDates);
            return scheduleList;
        },
        expandProjectList() {
            const todoListByProject = this.allTodoList;
            let allTodo = [];
            for (const [key, values] of Object.entries(todoListByProject)) {
                allTodo = allTodo.concat(values);
            }
            return allTodo;
        },
        checkIfThereIsADate(allTodo) {
            let checkedTodos = [];
            for (const [key, todo] of Object.entries(allTodo)) {
                if (todo.date) {
                    checkedTodos.push(todo);
                }
            }
            return checkedTodos;
        },
        sortTodoListByDate(todoListWithDates) {
            let scheduleList = todoListWithDates.sort(function (a, b) {
                return a.date < b.date ? -1 : 1; //オブジェクトの昇順ソート
            });
            console.info(scheduleList);
            return scheduleList;
        },
    },
};
</script>

<style scoped lang="sass">
.tabsStyle
  width: 772px
  position: absolute

.spTabsStyle
  width: 100%
  height: 40px
  position: absolute
  top: 64px

.spTabStyle
  width: 25%
  height: 40px
  font-size: 0.75rem

.cardStyle
  height: calc(100vh - 152px)
  position: relative
  top: 48px

.spCardStyle
  height: calc(100vh - 192px)
  position: relative
  top: 96px
</style>
