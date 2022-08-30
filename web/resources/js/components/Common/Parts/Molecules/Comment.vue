<template>
    <div class="d-flex">
        <v-list-item-avatar class="d-flex align-self-center my-auto mx-0">
            <v-avatar
                class="ma-1"
                color="brown"
                :size="$vuetify.breakpoint.smAndUp ? '30' : '28'"
            >
                <span
                    class="white--text 'text-h6"
                    :style="'font-size: 0.8rem !important;'"
                >
                    {{ getInitial(comment) }}
                </span>
            </v-avatar>
        </v-list-item-avatar>
        <div class="d-flex flex-column px-2">
            <div class="d-flex">
                <div class="d-flex" style="height: 24px">
                    <p
                        class="d-flex align-self-center font-weight-bold caption ma-0"
                        style="font-size: 0.65rem !important"
                    >
                        {{ comment.user_name }}
                    </p>
                </div>
                <div>
                    <v-subheader class="px-2" style="height: 24px">
                        <p
                            class="d-flex align-self-center font-weight-bold caption ma-0"
                            style="font-size: 0.5rem !important"
                        >
                            {{ getDateAndTime(comment) }}
                        </p>
                    </v-subheader>
                </div>
            </div>
            <div>
                <p
                    class="d-flex align-self-center font-weight-bold caption ma-0"
                    style="color: #424242; font-size: 0.95rem !important"
                >
                    {{ comment.text }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    data: () => ({}),
    props: {
        comment: {
            type: Object,
        },
    },
    computed: {},
    methods: {
        getInitial(comment) {
            return comment.user_name.charAt(0);
        },
        getDateAndTime(comment) {
            const dateAndTime = new Date(comment.created_at);
            const month = dateAndTime.getMonth() + 1;
            const date = dateAndTime.getDate();
            const hours = dateAndTime.getHours().toString().padStart(2, "0");
            const minutes = dateAndTime
                .getMinutes()
                .toString()
                .padStart(2, "0");
            return month + "月" + date + "日 " + hours + ":" + minutes;
        },
    },
};
</script>
