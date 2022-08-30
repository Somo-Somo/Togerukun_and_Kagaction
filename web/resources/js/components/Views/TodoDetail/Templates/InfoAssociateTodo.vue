<template>
    <div class="">
        <div class="d-flex justify-space-between flex-column">
            <tab :todoStatuses="linkedTodo" :tab="tab" @setTab="setTab"></tab>
            <v-subheader
                v-if="tab !== 2"
                class="px-md-0 mt-2 todoSubTitle"
                v-show="$vuetify.breakpoint.smAndUp"
            >
                <p
                    class="ma-0 font-weight-black caption align-self-center"
                    color="grey lighten-1"
                    style="
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    "
                    :style="
                        $vuetify.breakpoint.smAndUp
                            ? 'max-width: 70%'
                            : 'max-width: 40vw'
                    "
                >
                    「{{ todo.name }}
                </p>
                <p
                    class="ma-0 font-weight-black caption align-self-center"
                    color="grey lighten-1"
                >
                    {{ assistSubHeaderText(tab) }}
                </p>
            </v-subheader>
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
import TodoCards from "../components/Cards/TodoCard.vue";
import Comments from "../components/Comments.vue";
import Cause from "../components/Cause.vue";
import NewAdditionalCard from "../components/Cards/NewAddtionalCard.vue";

export default {
    components: {
        Tab,
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
    computed: {
        assistSubHeaderText() {
            return (tab) => {
                if (tab === 0) {
                    return "」を達成するには？";
                } else if (tab === 1) {
                    return "」が達成できない原因は？";
                }
            };
        },
    },
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

.todoSubTitle
  font-size: 1rem
  height: 36px

.spTodoSubTitle
  font-size: 12px
  height: 24px
  padding: 0 0 0 12px

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
