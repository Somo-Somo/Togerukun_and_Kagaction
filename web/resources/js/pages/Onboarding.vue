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
                    <template
                        class=""
                        v-for="(stepHeader, stepHeaderIndex) in stepHeaders"
                    >
                        <v-stepper-step
                            class="caption font-weight-bold"
                            :key="stepHeaderIndex"
                            :complete="stepHeaderIndex + 1 < step"
                            :step="stepHeaderIndex + 1"
                        >
                            {{ stepHeader.title }}
                        </v-stepper-step>
                        <v-divider
                            :key="stepHeaderIndex"
                            v-if="stepHeaderIndex + 1 !== 3"
                        ></v-divider>
                    </template>
                </v-stepper-header>
                <v-divider></v-divider>
                <v-stepper-items>
                    <template
                        v-for="(
                            stepQuestion, stepQuestionIndex
                        ) in stepQuestions"
                    >
                        <v-stepper-content
                            :step="stepQuestionIndex + 1"
                            :key="stepQuestionIndex"
                        >
                            <div>
                                <p
                                    class="font-weight-black"
                                    style="font-size: 1.75rem"
                                >
                                    {{ getQuestionTitle(stepQuestion) }}
                                </p>
                                <p
                                    class="subtitle-2 #757575--text"
                                    style="font-weight: 600 !important"
                                >
                                    {{ getQuestionSubTitle(stepQuestion) }}
                                </p>
                            </div>

                            <v-btn
                                color="primary"
                                @click="nextStep(stepQuestionIndex + 1)"
                            >
                                次へ
                            </v-btn>

                            <v-btn
                                text
                                v-if="step !== 1"
                                @click="prevStep(stepQuestionIndex + 1)"
                            >
                                戻る
                            </v-btn>
                        </v-stepper-content>
                    </template>
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
        steps: 3,
        stepHeaders: [
            { title: "プロジェクト作成" },
            { title: "ゴール作成" },
            { title: "日付の設定" },
        ],
        stepQuestions: [
            {
                title: "さんが現在取り組んでいることまたはこれから取り組みたいことは何ですか？",
                subTitle:
                    "仕事や普段の生活などで頑張りたいことや習慣にしたいこと、改善したいことでも大丈夫です。",
            },
            {
                title: "で達成したいゴールまたは目標は何ですか？",
                subTitle1:
                    "ゴールは後で変更や追加したりすることができます。気軽に",
                subTitle2: "の理想を思い浮かべて書いてみましょう。",
            },
            {
                title: "をいつまでに達成したいですか？",
                subTitle: "の期限を設けてみましょう。",
            },
        ],
    }),
    computed: {
        ...mapGetters({
            user: "auth/user",
        }),
        getQuestionTitle() {
            return (stepQuestion) => {
                if (this.step === 1) {
                    return this.user.name + stepQuestion.title;
                } else if (this.step === 2) {
                    return "「" + this.user.name + "」" + stepQuestion.title;
                } else if (this.step === 3) {
                    return "「" + this.user.name + "」" + stepQuestion.title;
                }
            };
        },
        getQuestionSubTitle() {
            return (stepQuestion) => {
                if (this.step === 1) {
                    return stepQuestion.subTitle;
                } else if (this.step === 2) {
                    return (
                        stepQuestion.subTitle1 +
                        "「" +
                        this.user.name +
                        "」" +
                        stepQuestion.subTitle2
                    );
                } else if (this.step === 3) {
                    return "「" + this.user.name + "」" + stepQuestion.subTitle;
                }
            };
        },
    },
    methods: {
        nextStep(stepQuestionNum) {
            return (this.step =
                stepQuestionNum === this.steps ? 1 : stepQuestionNum + 1);
        },
        prevStep(stepQuestionNum) {
            return (this.step = stepQuestionNum - 1);
        },
    },
};
</script>
<style scoped lang='sass'>
.v-stepper__header
  box-shadow: none !important
</style>