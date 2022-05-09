<template>
    <v-card class="ma-4" color="#26c6da">
        <v-list class="py-0">
            <v-list-item-group color="primary">
                <v-list-item
                    v-for="(item, i) in items"
                    :key="i"
                    style="height: 88px"
                >
                    <span>
                        <DashedLine />
                    </span>
                    <span>
                        <TDashedLine />
                    </span>
                    <div>
                        <div style="height: 28px">
                            <UpperOrLowerDashedLine />
                        </div>
                        <div style="height: 24px">
                            <v-btn icon height="24">
                                <v-icon
                                    >mdi-checkbox-marked-circle-outline</v-icon
                                >
                            </v-btn>
                        </div>
                        <div style="height: 28px">
                            <UpperOrLowerDashedLine />
                        </div>
                    </div>
                    <span>
                        <LDashedLine />
                    </span>
                    <v-list-item-content>
                        <v-list-item-title
                            v-text="item.text"
                        ></v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list-item-group>
        </v-list>
    </v-card>
</template>

<script>
import DashedLine from "../components/DashedLine/DashedLine.vue";
import LDashedLine from "../components/DashedLine/LDashedLine.vue";
import TDashedLine from "../components/DashedLine/TDashedLine.vue";
import UpperOrLowerDashedLine from "../components/DashedLine/UpperOrLowerDashedLine.vue";

export default {
    components: {
        DashedLine,
        LDashedLine,
        TDashedLine,
        UpperOrLowerDashedLine,
    },
    data: () => ({
        items: [
            { text: "テストで450点を取りたい", depth: "0" },
            { text: "英語で90点をとる", depth: "1" },
            { text: "英単語30個を勉強する", depth: "2" },
            { text: "毎朝バスの中でやる", depth: "3" },
            { text: "数学で90点をとる", depth: "1" },
            { text: "数学ノートをやる", depth: "2" },
            { text: "社会で90点を取る", depth: "1" },
        ],
    }),
    props: {},
    computed: {},
    methods: {},
};
</script>
