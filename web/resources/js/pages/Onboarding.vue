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
            <v-stepper v-model="e1">
                <v-stepper-header>
                    <v-stepper-step :complete="e1 > 1" step="1">
                        Name of step 1
                    </v-stepper-step>

                    <v-divider></v-divider>

                    <v-stepper-step :complete="e1 > 2" step="2">
                        Name of step 2
                    </v-stepper-step>

                    <v-divider></v-divider>

                    <v-stepper-step step="3"> Name of step 3 </v-stepper-step>
                </v-stepper-header>

                <v-stepper-items>
                    <v-stepper-content step="1">
                        <v-card
                            class="mb-12"
                            color="grey lighten-1"
                            height="200px"
                        ></v-card>

                        <v-btn color="primary" @click="e1 = 2">
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

                        <v-btn color="primary" @click="e1 = 3">
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

                        <v-btn color="primary" @click="e1 = 1">
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
        step : 1,
    }),
    computed: {
        ...mapGetters({
            user: "auth/user",
        }),
    },
    methods: {},
};
</script>
