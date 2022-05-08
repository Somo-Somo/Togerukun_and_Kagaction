<template>
    <v-simple-table>
        <template v-slot:default>
            <tbody>
                <tr v-for="item in items" :key="item.name">
                    <td>{{ item.name }}</td>
                    <td>{{ item.depth }}</td>
                </tr>
            </tbody>
        </template>
    </v-simple-table>
</template>

<script>
export default {
    data: () => ({
        items: [
            { name: "テストで450点を取りたい", depth: "0" },
            { name: "英語で90点をとる", depth: "1" },
            { name: "英単語30個を勉強する", depth: "2" },
            { name: "毎朝バスの中でやる", depth: "3" },
            { name: "数学で90点をとる", depth: "1" },
            { name: "数学ノートをやる", depth: "2" },
            { name: "社会で90点を取る", depth: "1" },
        ],
    }),
    props: {},
    computed: {},
    methods: {},
};
</script>
