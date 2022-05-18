<template>
    <div class="d-flex" style="max-height: 88px">
        <div class="d-flex">
            <div class="d-flex" style="width: 50px" v-if="todo.depth === 0">
                <svg
                    class="my-auto ml-2"
                    style="width: 28px; height: 28px"
                    viewBox="0 0 24 24"
                >
                    <path
                        fill="currentColor"
                        d="M14.4,6H20V16H13L12.6,14H7V21H5V4H14L14.4,6M14,14H16V12H18V10H16V8H14V10L13,8V6H11V8H9V6H7V8H9V10H7V12H9V10H11V12H13V10L14,12V14M11,10V8H13V10H11M14,10H16V12H14V10Z"
                    />
                </svg>
            </div>
            <v-spacer style="width: 50px" v-if="todo.depth !== 0"></v-spacer>
        </div>
        <v-list-item-content
            class="d-flex py-0"
            v-for="num in todo.depth"
            :key="num"
        >
            <div class="d-flex">
                <TDashedLine
                    v-if="
                        todo.depth === num &&
                        !todo.leftSideOfLine[num].lastChild
                    "
                />
                <LDashedLine
                    v-if="
                        todo.depth === num && todo.leftSideOfLine[num].lastChild
                    "
                />
                <DashedLine
                    v-if="
                        todo.depth !== num &&
                        !todo.leftSideOfLine[num].lastChild
                    "
                />
                <v-spacer
                    style="width: 50px"
                    v-if="
                        todo.depth !== num && todo.leftSideOfLine[num].lastChild
                    "
                ></v-spacer>
            </div>
        </v-list-item-content>
        <div class="d-flex flex-column" style="width: 28px; height: 88px">
            <v-list-item-action
                class="d-flex mx-auto"
                :class="todo.child ? 'mt-auto mb-1' : 'my-auto'"
                style="height: 24px;"
                v-model="todo.accomplish"
                @click.stop="onClickAccomplish(todo)"
            >
                <v-btn
                    icon
                    height="24"
                    width="24"
                    :color="todo.accomplish ? 'green' : ''"
                >
                    <v-icon>mdi-checkbox-marked-circle-outline</v-icon>
                </v-btn>
            </v-list-item-action>
            <div class="d-flex mx-auto" v-if="todo.child" height="32">
                <LowerDashedLine />
            </div>
        </div>
    </div>
</template>

<script>
import DashedLine from "../DashedLine/DashedLine.vue";
import LDashedLine from "../DashedLine/LDashedLine.vue";
import TDashedLine from "../DashedLine/TDashedLine.vue";
import LowerDashedLine from "../DashedLine/LowerDashedLine.vue";

export default {
    components: {
        DashedLine,
        LDashedLine,
        TDashedLine,
        LowerDashedLine,
    },
    data: () => ({}),
    props: {
        todo: {
            type: Object,
        },
    },
    computed: {},
    methods: {
        onClickAccomplish(todo) {
            this.$set(todo,'accomplish', todo.accomplish ? false : true);
            this.$store.dispatch("todo/updateAccomplish", todo);
        },
    },
    watch: {
        'todo.accomplish' (next,prev) {
            return;
        }
    },
};
</script>
