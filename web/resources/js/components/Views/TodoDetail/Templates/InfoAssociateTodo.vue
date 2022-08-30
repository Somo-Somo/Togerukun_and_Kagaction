<template>
    <div class="">
        <div class="d-flex justify-space-between flex-column">
            <tab :todoStatuses="linkedTodo" :tab="tab" @setTab="setTab"></tab>
            <assist-sub-header :tab="tab" :todo="todo"></assist-sub-header>
        </div>
        <div
            class="overflow-y-auto d-flex flex-column"
            :class="$vuetify.breakpoint.mdAndUp ? 'cardStyle' : 'spCardStyle'"
        >
            <TodoCards
                v-if="tab === 0"
                :project="project"
                :selectTodo="todo"
                :todoList="todoList"
                :todoStatus="linkedToDo[0]"
            />
            <Cause v-if="tab === 1" :todo="todo" />
            <Comments
                v-if="tab === 2 && todo.comments.length > 0"
                :todo="todo"
            />
            <!-- PC版追加カード -->
            <NewAdditionalCard
                @clickAditional="onClickCreate"
                :category="linkedToDo[tab].name"
            />
        </div>
    </div>
</template>

<script>
import Tab from "../../../Common/Parts/Molecules/Tab.vue";
import AssistSubHeader from "../Parts/AssistSubHeader.vue";
import TodoCards from "../../../Common/Template/TodoCards.vue";
import Comments from "../components/Comments.vue";
import Cause from "../components/Cause.vue";
import NewAdditionalCard from "../components/Cards/NewAddtionalCard.vue";

export default {
    components: {
        Tab,
        AssistSubHeader,
        TodoCards,
        Comments,
        Cause,
        NewAdditionalCard,
    },
    data: () => ({
        form: false,
    }),
    props: {
        tab: {
            type: Number,
        },
        todo: {
            type: Object,
        },
        parentTodo: {
            type: Object,
        },
        linkedToDo: {
            type: Array,
        },
        todoList: {
            type: Array,
        },
        project: {
            type: Object,
        },
    },
    computed: {},
    methods: {
        setTab(newVal) {
            return this.$emit("setTab", newVal);
        },
        onClickCreate() {
            this.$store.dispatch("form/onClickCreate");
            this.form = true;
        },
    },
    watch: {},
};
</script>

<style scoped lang="sass">
.spTabStyle
  width: 25%
  height: 36px
  font-size: 0.75rem

.cardStyle
  height: calc(100vh - 376px)
  position: relative

.spCardStyle
  height: calc(100vh - 320px)
  position: relative
</style>
