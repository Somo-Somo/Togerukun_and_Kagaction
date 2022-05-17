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
                            stepQuestionAndAnswer, stepQuestionAndAnswerIndex
                        ) in stepQuestionsAndAnswers"
                    >
                        <v-stepper-content
                            :step="stepQuestionAndAnswerIndex + 1"
                            :key="stepQuestionAndAnswerIndex"
                        >
                            <div>
                                <p
                                    class="font-weight-black"
                                    style="font-size: 1.75rem"
                                >
                                    {{ getQuestion(stepQuestionAndAnswer) }}
                                </p>
                                <p
                                    class="subtitle-2 #757575--text ma-0"
                                    style="font-weight: 600 !important"
                                >
                                    {{ getAddition(stepQuestionAndAnswer) }}
                                </p>
                                <v-text-field
                                    class="py-4"
                                    v-if="step !== 3"
                                    v-model="stepQuestionAndAnswer.answer"
                                    counter="64"
                                    :hint="stepQuestionAndAnswer.hint"
                                ></v-text-field>
                            </div>

                            <v-btn
                                color="primary"
                                @click="nextStep(stepQuestionAndAnswerIndex + 1)"
                            >
                                次へ
                            </v-btn>

                            <v-btn
                                text
                                v-if="step !== 1"
                                @click="prevStep(stepQuestionAndAnswerIndex + 1)"
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
        stepQuestionsAndAnswers: [
            {
                question: "さんが現在取り組んでいることまたはこれから取り組みたいことは何ですか？",
                addition:
                    "仕事や普段の生活などで頑張りたいことや習慣にしたいこと、改善したいことでも大丈夫です。",
                answer: null,
                hint: "例: WEB開発, 試験勉強 etc",
            },
            {
                question: "で達成したいゴールまたは目標は何ですか？",
                addition1:
                    "ゴールは後で変更や追加したりすることができます。気軽に",
                addition2: "の理想を思い浮かべて書いてみましょう。",
                answer: null,
                hint: null,
            },
            {
                question: "をいつまでに達成したいですか？",
                addition: "の期限を設けてみましょう。",
                answer: null,
                label: null,
            },
        ],
    }),
    computed: {
        ...mapGetters({
            user: "auth/user",
        }),
        getQuestion() {
            return (stepQuestionAndAnswer) => {
                if (this.step === 1) {
                    return this.user.name + stepQuestionAndAnswer.question;
                } else if (this.step === 2) {
                    return "「" + this.user.name + "」" + stepQuestionAndAnswer.question;
                } else if (this.step === 3) {
                    return "「" + this.user.name + "」" + stepQuestionAndAnswer.question;
                }
            };
        },
        getAddition() {
            return (stepQuestionAndAnswer) => {
                if (this.step === 1) {
                    return stepQuestionAndAnswer.addition;
                } else if (this.step === 2) {
                    return (
                        stepQuestionAndAnswer.addition1 +
                        "「" +
                        this.user.name +
                        "」" +
                        stepQuestionAndAnswer.addition2
                    );
                } else if (this.step === 3) {
                    return "「" + this.user.name + "」" + stepQuestionAndAnswer.addition;
                }
            };
        },
    },
    methods: {
        nextStep(stepQuestionAndAnswerNum) {
            return (this.step =
                stepQuestionAndAnswerNum === this.steps ? 1 : stepQuestionAndAnswerNum + 1);
        },
        prevStep(stepQuestionAndAnswerNum) {
            return (this.step = stepQuestionAndAnswerNum - 1);
        },
    },
};
</script>
<style scoped lang="sass">
.v-stepper__header
  box-shadow: none !important
</style>
