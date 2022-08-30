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
            <todo-cards
                v-if="tab === 0"
                :project="project"
                :selectTodo="todo"
                :todoList="todoList"
                :todoStatus="linkedTodo[0]"
            ></todo-cards>
            <todo-causes v-if="tab === 1" :todo="todo"></todo-causes>
            <todo-comments
                v-if="tab === 2 && todo.comments.length > 0"
                :todo="todo"
            ></todo-comments>
            <!-- PC版追加カード -->
            <new-additional-card
                :category="linkedTodo[tab].name"
                @clickAditional="clickAdditional"
            ></new-additional-card>
        </div>
    </div>
</template>

<script>
import Tab from "../../../Common/Parts/Molecules/Tab.vue";
import AssistSubHeader from "../Parts/AssistSubHeader.vue";
import TodoCards from "../../../Common/Template/TodoCards.vue";
import TodoComments from "../Parts/TodoComments.vue";
import TodoCauses from "../Parts/TodoCauses.vue";
import NewAdditionalCard from "../../../Common/Parts/Molecules/NewAdditionalCard.vue";

export default {
    components: {
        Tab,
        AssistSubHeader,
        TodoCards,
        TodoComments,
        TodoCauses,
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
        linkedTodo: {
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
        clickAdditional() {
            this.$emit("onClickCreate");
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
