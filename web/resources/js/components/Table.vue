<template>
    <v-card class="ma-4" color="#26c6da">
        <v-list class="py-0">
            <v-list-item-group color="primary">
                <v-list-item v-for="(item, i) in items" :key="i" style="height:80px">
                    <span>
                        <svg width="100" height="80" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M50.25 0V100" stroke="black" stroke-dasharray="6 6"/>
                        <path d="M100 50.25H52" stroke="black" stroke-dasharray="6 6"/>
                        </svg>
                    </span>
                    <v-list-item-content>
                        <v-list-item-title
                            v-text="item.depth"
                        ></v-list-item-title>
                    </v-list-item-content>
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
export default {
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