<template>
    <v-dialog
        v-model="inputForm"
        @click:outside="$emit('onClickCancel')"
        width="500"
    >
        <v-card ref="form">
            <v-card-text
                class="d-flex align-content-center pb-0"
                :class="step === 1 ? 'px-9 pt-9' : 'px-6 pt-4'"
            >
                <v-text-field
                    v-if="step === 1"
                    class="pa-0 ma-0"
                    v-model="text"
                    counter="64"
                    :label="formLabel"
                    clearable
                ></v-text-field>
                <calender-form
                    :date="date"
                    :dateLabel="text + 'の日付'"
                    v-if="step === 2"
                    @onClickSave="updateDate"
                    @onClickRemove="removeDate"
                />
                <v-btn
                    class="my-auto"
                    style="position: absolute; right: 20px; top: 38px"
                    v-if="step === 2"
                    @click="removeDate()"
                    small
                    icon
                >
                    <v-icon :size="$vuetify.breakpoint.smAndUp ? '24' : '14'"
                        >mdi-close</v-icon
                    >
                </v-btn>
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
                <v-btn
                    @click="step === 1 ? $emit('onClickCancel') : onClickBack()"
                    text
                >
                    {{ step === 1 ? "キャンセル" : "戻る" }}
                </v-btn>
                <v-spacer></v-spacer>
                <v-slide-x-reverse-transition> </v-slide-x-reverse-transition>
                <v-btn
                    color="primary"
                    :disabled="!text || loading"
                    :loading="loading"
                    @click="btnStateIsNext ? onClickNext(text) : onClickDone()"
                    text
                >
                    {{ btnStateIsNext ? "次へ" : "完了" }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import CalenderForm from "../Molecules/CalenderForm.vue";

export default {
    components: { CalenderForm },
    data: () => ({
        step: 1,
        text: null,
        date: null,
        cancel: true,
        done: true,
    }),
    props: {
        formCategory: {
            type: String,
        },
        formLabel: {
            type: String,
        },
        inputForm: {
            type: Boolean,
        },
        loading: {
            type: Boolean,
        },
    },
    computed: {
        formName: {
            get() {
                return this.$store.getters["form/name"];
            },
            set(value) {
                this.$store.dispatch("form/setName", value);
            },
        },
        btnStateIsNext() {
            return this.formCategory === "ToDo" && this.step === 1
                ? true
                : false;
        },
        dateLabel() {
            return () => {
                return this.text ? this.text + "の日付" : null;
            };
        },
    },
    methods: {
        onClickBack() {
            this.step = 1;
        },
        onClickNext(text) {
            this.text = text;
            this.step = 2;
        },
        onClickDone() {
            this.$emit("submitForm", {
                text: this.text,
                date: this.date,
            });
        },
        updateDate(date) {
            this.date = date;
        },
        removeDate() {
            this.date = null;
        },
    },
    watch: {
        // ダイアログが閉じた後フォームの値を全て空にする * computedに移行したい
        inputForm(inputForm) {
            if (!inputForm) {
                this.formName = "";
            }
        },
    },
};
</script>
