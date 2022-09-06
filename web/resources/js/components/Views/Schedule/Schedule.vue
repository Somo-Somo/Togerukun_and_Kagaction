<template>
    <v-container
        class="d-flex flex-column py-6 my-md-2 px-md-16"
        style="max-width: 900px"
        fluid
    >
        <main-header :headerTitle="'予定'"></main-header>
        <template>
            <div
                class="d-flex justify-space-between"
                :class="
                    $vuetify.breakpoint.mdAndUp ? 'tabsStyle' : 'spTabsStyle'
                "
            >
                <tab :tab="tab" :todoStatuses="periods" @setTab="setTab"></tab>
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

                <schedule-cards
                    :projectList="projectList"
                    :scheduleList="scheduleList"
                    :period="periods[tab]"
                    :loading="loading"
                ></schedule-cards>
            </div>
        </template>
    </v-container>
</template>

<script>
import MainHeader from "../../Common/Parts/Organisms/MainHeader.vue";
import Tab from "../../Common/Parts/Molecules/Tab.vue";
import ScheduleCards from "./Parts/ScheduleCards.vue";
import { mapGetters } from "vuex";

export default {
    components: {
        MainHeader,
        Tab,
        ScheduleCards,
    },
    data: () => ({
        category: "ToDo",
        tab: 0,
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
            const todosHasDate = this.expandProjectList();
            const scheduleList = this.sortTodoListByDate(todosHasDate);
            return scheduleList;
        },
        expandProjectList() {
            const todoListByProject = this.allTodoList;
            let todosHasDate = [];
            for (const [projectUuid, todos] of Object.entries(
                todoListByProject
            )) {
                if (todos) {
                    const projectTodos = this.checkIfThereIsADate(
                        projectUuid,
                        todos
                    );
                    todosHasDate = todosHasDate.concat(projectTodos);
                }
            }
            return todosHasDate;
        },
        checkIfThereIsADate(projectUuid, todos) {
            const todosHasDate = [];
            for (const todo of todos) {
                if (todo.date) {
                    todo.projectUuid = projectUuid;
                    todosHasDate.push(todo);
                }
            }
            return todosHasDate;
        },
        sortTodoListByDate(todoListWithDates) {
            let scheduleList = todoListWithDates.sort(function (a, b) {
                return a.date < b.date ? -1 : 1; //オブジェクトの昇順ソート
            });
            return scheduleList;
        },
        setTab(newVal) {
            return (this.tab = newVal);
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


.cardStyle
  height: calc(100vh - 152px)
  position: relative
  top: 48px

.spCardStyle
  height: calc(100vh - 192px)
  position: relative
  top: 96px
</style>
