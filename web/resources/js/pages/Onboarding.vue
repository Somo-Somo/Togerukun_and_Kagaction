<template>
    <v-container
        class="d-flex flex-column my-6 my-md-2 px-md-16"
        style="max-width: 900px"
        fluid
    >
        <Header
            :headerTitle="'Kagactionへようこそ'"
            v-if="$vuetify.breakpoint.mdAndUp"
        />
        <div>
            <v-stepper v-model="step">
                <v-stepper-header style="width: 100%">
                    <template class="" v-for="(stepTitle, i) in stepTitles">
                        <v-stepper-step
                            class="caption font-weight-bold"
                            :key="i"
                            :complete="i + 1 < step"
                            :step="i + 1"
                        >
                            {{ stepTitle.title }}
                        </v-stepper-step>
                        <v-divider
                            :key="i"
                            v-if="i + 1 !== 3"
                        ></v-divider>
                    </template>
                </v-stepper-header>

                <v-stepper-items>
                    <v-stepper-content step="1">
                        <v-card
                            class="mb-12"
                            color="grey lighten-1"
                            height="200px"
                        ></v-card>

                        <v-btn color="primary" @click="step = 2">
                            Continue
                        </v-btn>

                        <v-btn text> Cancel </v-btn>
                    </v-stepper-content>

                    <v-stepper-content step="2">
                        <v-card
                            class="mb-12"
                            color="grey lighten-1"
                            height="200px"
                        ></v-card>

                        <v-btn color="primary" @click="step = 3">
                            Continue
                        </v-btn>

                        <v-btn text> Cancel </v-btn>
                    </v-stepper-content>

                    <v-stepper-content step="3">
                        <v-card
                            class="mb-12"
                            color="grey lighten-1"
                            height="200px"
                        ></v-card>

                        <v-btn color="primary" @click="step = 1">
                            Continue
                        </v-btn>

                        <v-btn text> Cancel </v-btn>
                    </v-stepper-content>
                </v-stepper-items>
            </v-stepper>
        </div>
    </v-container>
</template>

<script>
import Header from "../components/Header.vue";
import { mapGetters } from "vuex";

export default {
    components: {
        Header,
    },
    data: () => ({
        step: 1,
        stepTitles: [
            { title: "プロジェクト作成" },
            { title: "ゴール作成" },
            { title: "日付の設定" },
        ],
    }),
    computed: {
        ...mapGetters({
            user: "auth/user",
        }),
    },
    methods: {},
};
</script>
