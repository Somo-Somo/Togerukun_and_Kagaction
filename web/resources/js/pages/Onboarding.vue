<template>
    <v-container
        class="d-flex flex-column my-6 my-md-2 px-md-16"
        style="max-width: 900px"
        fluid
    >
        <Header :headerTitle="'Kagactionへようこそ'" v-if="$vuetify.breakpoint.mdAndUp" />
        <div>
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
    data: () => ({}),
    computed: {
        ...mapGetters({
            user: "auth/user",
        }),
    },
    methods: {},
};
</script>
