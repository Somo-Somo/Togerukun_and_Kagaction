<template>
    <div>
        <v-list-item-subtitle class="d-flex align-content-start mt-3 mb-1">
            <div
                v-if="dateTitle"
                class="d-flex"
                :style="dateDetail.backGroundColor"
            >
                <v-icon
                    :size="dateDetail.iconSize"
                    :color="dateDetail.iconColor"
                    >{{ dateDetail.icon }}</v-icon
                >
                <p
                    class="ma-0 px-2 font-weight-bold align-self-center"
                    :class="dateDetail.fontColor"
                    :style="
                        $vuetify.breakpoint.smAndUp
                            ? 'font-size:12px'
                            : 'font-size:8px'
                    "
                >
                    {{ dateTitle }}
                </p>
            </div>
            <div class="d-flex" style="max-width: 66%">
                <p
                    class="ma-0 grey--text font-weight-bold align-self-center"
                    style="
                        font-size: 8px;
                        max-width: 100%;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    "
                >
                    {{ todoSubTitle }}
                </p>
            </div>
        </v-list-item-subtitle>
        <v-list-item-title class="py-2 pb-4">
            <p
                class="font-weight-black ma-0"
                style="
                    max-width: calc(100% - 36px);
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                "
                :style="
                    $vuetify.breakpoint.smAndUp
                        ? 'font-size:1rem'
                        : 'font-size:0.8rem'
                "
            >
                {{ todoTitle }}
            </p></v-list-item-title
        >
    </div>
</template>

<script>
export default {
    components: {},
    data: () => ({}),
    props: {
        todoTitle: {
            type: String,
        },
        todoSubTitle: {
            type: String,
        },
        dateTitle: {
            type: String,
        },
        dateDetail: {
            type: Object,
        },
    },
    computed: {},
    methods: {},
};
</script>
