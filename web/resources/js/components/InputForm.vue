<template>
    <v-dialog
        v-model="inputForm"
        @click:outside="$emit('onClickCancel')"
        width="500"
    >
        <v-card ref="form">
            <v-card-text class="d-flex align-content-center px-9 pt-9 pb-0">
                <v-text-field
                    class="pa-0 ma-0"
                    v-model="formName"
                    counter="64"
                    :label="category"
                    @keypress.enter.prevent="$emit('submitForm')"
                    clearable
                ></v-text-field>
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
                    v-if="!dateForm || step === 2"
                    type="submit"
                    :loading="loading"
                    :disabled="!formName || loading"
                    color="primary"
                    @click="onClickDone"
                    text
                >
                    完了
                </v-btn>
                <v-btn
                    v-if="dateForm && step === 1"
                    :disabled="!formName || loading"
                    color="primary"
                    @click="onClickNext()"
                    text
                >
                    次へ
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    data: () => ({
        step: 1,
        text: null,
        date: null,
        cancel: true,
        done: true,
    }),
    props: {
        category: {
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
        dateForm() {
            return () => {
                return this.category !== "プロジェクト" ? true : false;
            };
        },
    },
    methods: {
        onClickBack() {
            this.step = 1;
        },
        onClickNext() {
            this.step = 2;
        },
        onClickDone() {
            this.$emit("submitForm", this.text);
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
