<template>
    <div class="d-flex px-1">
        <div class="d-flex align-self-center">
            <v-avatar
                color="brown"
                :size="$vuetify.breakpoint.smAndUp ? '32' : '28'"
            >
                <span
                    class="white--text 'text-h6"
                    :style="
                        $vuetify.breakpoint.smAndUp
                            ? 'font-size: 1rem !important;'
                            : 'font-size:0.8rem !important;'
                    "
                >
                    A
                </span>
            </v-avatar>
        </div>
        <div class="d-flex flex-column px-2 py-4">
            <div class="d-flex">
                <div class="d-flex" style="height: 24px">
                    <p
                        class="d-flex align-self-center font-weight-bold caption ma-0"
                        style="font-size: 0.75rem !important;"
                    >
                        Yujin Shitogishi
                    </p>
                </div>
                <div>
                    <v-subheader class="px-2" style="height: 24px">
                        <p
                            class="d-flex align-self-center font-weight-bold caption ma-0"
                            style="font-size: 0.5rem !important"
                        >
                            4月30日 10:00
                        </p>
                    </v-subheader>
                </div>
            </div>
            <div>
                <p
                    class="d-flex align-self-center font-weight-bold caption ma-0"
                    style="color: #424242; font-size: 0.9rem !important;"
                >
                    私の名前は涙そうそう
                </p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({}),
    props: {
        hypothesis: {
            type: Object,
        },
    },
    methods: {},
};
</script>
