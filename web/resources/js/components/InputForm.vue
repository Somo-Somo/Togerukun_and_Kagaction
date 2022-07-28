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
                <Calender
                    :todo="(todo = { name: name, date: date })"
                    :dateLabel="text + 'の日付'"
                    v-if="step === 2"
                    @onClickSave="updateDate"
                    @onClickRemove="removeDate"
                />
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
                <v-btn v-if="step === 1" @click="$emit('onClickCancel')" text>
                    キャンセル
                </v-btn>
                <v-btn v-if="step === 2" @click="onClickBack()" text>
                    戻る
                </v-btn>
                <v-spacer></v-spacer>
                <v-slide-x-reverse-transition> </v-slide-x-reverse-transition>
                <v-btn
                    v-if="!displayDateForm || step === 2"
                    type="submit"
                    :loading="loading"
                    :disabled="!text || loading"
                    color="primary"
                    @click="onClickDone()"
                    text
                >
                    完了
                </v-btn>
                <v-btn
                    v-if="displayDateForm && step === 1"
                    :disabled="!text || loading"
                    color="primary"
                    @click="onClickNext(text)"
                    text
                >
                    次へ
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import Calender from "./Calender.vue";
export default {
    components: { Calender },
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
        hypotheses: {
            type: Object,
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
        displayDateForm() {
            return this.formCategory === "ToDo" ? true : false;
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
