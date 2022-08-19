<template>
    <v-menu
        style="width: 100%"
        ref="calenderMenu"
        v-model="calenderMenu"
        :close-on-content-click="false"
        transition="scale-transition"
        offset-y
        min-width="auto"
    >
        <template class="d-flex" v-slot:activator="{ on, attrs }">
            <v-text-field
                class="d-flex align-self-center ma-0 pt-5"
                v-model="todo.date"
                :label="dateLabel"
                prepend-icon="mdi-calendar"
                readonly
                v-bind="attrs"
                v-on="on"
            >
            </v-text-field>
            <!-- <div
                class="d-flex align-self-center"
                style="z-index: 100"
                :style="
                    todo.name
                        ? 'position: relative; right: 20px'
                        : 'position: absolute; right: 24px'
                "
            >
                <v-btn @click="onClickRemove(todo.date)" small icon>
                    <v-icon :size="$vuetify.breakpoint.smAndUp ? '20' : '14'"
                        >mdi-close</v-icon
                    >
                </v-btn>
            </div> -->
        </template>
        <v-date-picker v-model="todo.date" no-title scrollable>
            <v-spacer></v-spacer>
            <cancel-btn :btnName="'キャンセル'" @click="calenderMenu = false" />
            <done-btn
                :btnName="'保存'"
                :disabled="false"
                :loading="false"
                @click="onClickSave(todo.date)"
            />
        </v-date-picker>
    </v-menu>
</template>

<script>
import CancelBtn from "../Atom/Btn/CancelBtn.vue";
import DoneBtn from "../Atom/Btn/DoneBtn.vue";

export default {
    components: {
        CancelBtn,
        DoneBtn,
    },
    data: () => ({
        calenderMenu: false,
    }),
    props: {
        todo: {
            type: Object,
        },
        dateLabel: {
            type: String,
        },
    },
    computed: {},
    methods: {
        onClickSave(date) {
            this.calenderMenu = false;
            this.$emit("onClickSave", date);
        },
        onClickRemove(date) {
            if (date) this.$emit("onClickRemove");
        },
    },
};
</script>
