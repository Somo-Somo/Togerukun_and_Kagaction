<template>
    <div class="d-flex">
        <div class="d-flex" v-if="todo.depth === 0"> 
            <div class="d-flex" style="width: 50px">
                <svg
                    class="ma-auto"
                    style="width: 28px; height: 28px"
                    viewBox="0 0 24 24"
                >
                    <path
                        fill="currentColor"
                        d="M14.4,6H20V16H13L12.6,14H7V21H5V4H14L14.4,6M14,14H16V12H18V10H16V8H14V10L13,8V6H11V8H9V6H7V8H9V10H7V12H9V10H11V12H13V10L14,12V14M11,10V8H13V10H11M14,10H16V12H14V10Z"
                    />
                </svg>
            </div>
        </div>
        <v-list-item-content
            class="d-flex"
            v-for="depth in todo.depth"
            :key="depth"
        >
            <div class="d-flex">
                <TDashedLine />
            </div>
        </v-list-item-content>
        <div class="d-flex ma-auto" style="width: 24px; height: 80px">
            <div
                class="d-flex align-content-center ma-auto"
                style="height: 24px"
            >
                <v-btn icon height="24" width="24">
                    <v-icon>mdi-checkbox-marked-circle-outline</v-icon>
                </v-btn>
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
    methods: {},
};
</script>
