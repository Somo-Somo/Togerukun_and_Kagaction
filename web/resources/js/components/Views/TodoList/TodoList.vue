<template>
    <div>
        <TodoHeader :project="project" />
        <v-container class="d-flex flex-column py-0 px-16" fluid>
            <div
                class="d-flex justify-space-between"
                :class="
                    $vuetify.breakpoint.mdAndUp ? 'tabsStyle' : 'spTabsStyle'
                "
            >
                <div class="px-4 pt-2">
                    <Tab
                        :todoStatuses="todoStatuses"
                        :tab="tab"
                        @setTab="setTab"
                    />
                </div>
            </div>
            <template>
                <!-- PC版 -->
                <div
                    class="d-flex flex-column px-2 px-md-0"
                    :class="
                        $vuetify.breakpoint.mdAndUp
                            ? 'cardStyle'
                            : 'spCardStyle'
                    "
                >
                    <v-tabs-items v-model="tab">
                        <v-tab-item
                            v-for="todoStatus in todoStatuses"
                            :key="todoStatus.name"
                        >
                            <todo-cards
                                v-if="tab !== 0"
                                :project="project"
                                :todoList="todoList"
                                :todoStatus="todoStatuses[tab]"
                            />
                            <todo-tree-table
                                v-if="tab === 0"
                                :project="project"
                                :todoList="todoList"
                                @onClickCreate="onClickCreate"
                            />
                            <!-- PC版追加カード -->
                            <NewAdditionalCard
                                class="px-4 py-1"
                                v-if="tab === 0 && todoList.length === 0"
                                @clickAditional="onClickCreate"
                                :category="'ゴール'"
                            />
                        </v-tab-item>
                    </v-tabs-items>
                </div>
            </template>
            <form class="form" @submit.prevent="submitForm()">
                <input-form
                    v-if="form"
                    @onClickCancel="onClickCancel"
                    @submitForm="submitForm"
                    :formCategory="category"
                    :formLabel="formLabel"
                    :inputForm="inputForm"
                    :loading="submitLoading"
                />
            </form>
        </v-container>
    </div>
</template>

<script>
import TodoHeader from "../../Header.vue";
import Tab from "../../Common/Parts/Molecules/Tab.vue";
import TodoTreeTable from "./Templates/TodoTreeTable.vue";
import TodoCards from "../../Common/Template/TodoCards.vue";
import NewAdditionalCard from "../../Common/Parts/Molecules/NewAdditionalCard.vue";
import InputForm from "../../Common/Parts/Organisms/InputForm.vue";
import { mapGetters, mapState } from "vuex";

export default {
    components: {
        TodoHeader,
        Tab,
        TodoTreeTable,
        TodoCards,
        NewAdditionalCard,
        InputForm,
    },
    data: () => ({
        tab: null,
        todoStatuses: [
            { name: "ToDoツリー", show: false },
            { name: "予定", show: false },
        ],
        show: false,
        submitLoading: false,
        form: false,
        category: "ToDo",
    }),
    computed: {
        ...mapState({
            apiStatus: (state) => state.auth.apiStatus,
        }),
        ...mapGetters({
            name: "form/name",
            inputForm: "form/inputForm",
            project: "project/project",
            todoList: "todo/todoList",
        }),
        formLabel() {
            return this.project.name + "のゴール";
        },
    },
    methods: {
        setTab(newVal) {
            return (this.tab = newVal);
        },
        onClickCreate() {
            this.$store.dispatch("form/onClickCreate");
            this.form = true;
        },
        onClickCancel() {
            this.$store.dispatch("form/closeForm");
            this.form = false;
        },
        submitForm(form) {
            this.$store.dispatch("form/closeForm");
            this.$store.dispatch("todo/createGoal", {
                project: this.project,
                name: form.text,
                date: form.date,
            });
            this.form = false;
        },
    },
    watch: {
        // ダイアログが閉じた後フォームの値を全て空にする * computedに移行したい
        inputForm(inputForm) {
            if (!inputForm) {
                this.form = false;
            }
        },
    },
};
</script>

<style scoped lang="sass">
.tabsStyle
  width: 772px

.spTabStyle
  width: 25%
  height: 40px
  font-size: 0.75rem

.cardStyle
  height: calc(100vh - 120px)
  position: relative

.spCardStyle
  height: calc(100vh - 192px)
  position: relative
</style>

<style lang="sass">
.v-slide-group__prev
  display: none !important
.v-slide-group__next
  display: none !important
</style>
