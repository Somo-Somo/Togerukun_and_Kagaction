<template>
    <div>
        <v-list class="py-0" width="100%">
            <v-col
                :style="
                    $vuetify.breakpoint.smAndUp
                        ? 'padding:8px 0px'
                        : 'padding:8px'
                "
            >
                <v-card
                    class="rounded"
                    @click="$emit('clickAditional')"
                    outlined
                >
                    <v-list
                        class="py-0"
                        :style="
                            $vuetify.breakpoint.smAndUp
                                ? 'height:80px'
                                : 'height:64px'
                        "
                    >
                        <v-list-item
                            class="d-flex justify-space-around"
                            :style="
                                $vuetify.breakpoint.smAndUp
                                    ? 'height:80px'
                                    : 'height:64px'
                            "
                            link
                        >
                            <v-icon class="pa-1">mdi-plus</v-icon>
                            <v-list-item-content>
                                <v-list-item-title>
                                    <p
                                        class="grey--text font-weight-bold ma-0 pa-2"
                                        :style="
                                            $vuetify.breakpoint.smAndUp
                                                ? 'font-size:1rem'
                                                : 'font-size:0.75rem'
                                        "
                                    >
                                        {{ category }}を追加する
                                    </p>
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-card>
            </v-col>
        </v-list>
    </div>
</template>

<script>
export default {
    props: {
        category: {
            type: String,
        },
    },
};
</script>
