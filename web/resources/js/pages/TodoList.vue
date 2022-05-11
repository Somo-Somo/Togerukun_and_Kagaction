<template>
    <div>
        <Header :project="project" />
        <Tab :todoStatuses="todoStatuses" :tab="tab" @setTab="setTab" />
        <v-container class="d-flex flex-column py-2 px-16" fluid>
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
                    <v-tabs-items class="overflow-y-auto" v-model="tab">
                        <v-tab-item
                            v-for="todoStatus in todoStatuses"
                            :key="todoStatus.name"
                        >
                            <TodoCards
                                v-if="tab !== 0"
                                :project="project"
                                :todoList="todoList"
                                :todoStatus="todoStatuses[tab]"
                            />
                            <Table
                                v-if="tab === 0"
                                :project="project"
                                :todoList="todoList"
                                @onClickCreate="onClickCreate"
                            />
                            <!-- PC版追加カード -->
                            <NewAdditionalCard
                                v-if="tab === 0 && todoList.length === 0"
                                @clickAditional="onClickCreate"
                                :category="'ゴール'"
                            />
                        </v-tab-item>
                    </v-tabs-items>
                </div>
            </template>
            <form class="form" @submit.prevent="submitForm()">
                <InputForm
                    v-if="form"
                    @onClickCancel="onClickCancel"
                    @submitForm="submitForm"
                    :category="'ゴール'"
                    :inputForm="inputForm"
                    :loading="submitLoading"
                />
            </form>
        </v-container>
    </div>
</template>

<script>
import Header from "../components/Header.vue";
import Tab from "../components/Tab.vue";
import Table from "../components/Table/Table.vue";
import TodoCards from "../components/Cards/TodoCard.vue";
import NewAdditionalCard from "../components/Cards/NewAddtionalCard.vue";
import SpBottomBtn from "../components/Buttons/SpBottomBtn.vue";
import InputForm from "../components/InputForm.vue";
import { mapGetters, mapState } from "vuex";

export default {
    components: {
        Header,
        Tab,
        Table,
        TodoCards,
        NewAdditionalCard,
        SpBottomBtn,
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
        submitForm() {
            this.$store.dispatch("form/closeForm");
            this.$store.dispatch("todo/createGoal", {
                project: this.project,
                todoName: this.name,
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
