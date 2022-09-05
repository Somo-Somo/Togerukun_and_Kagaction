<template>
    <div>
        <template
            v-for="(
                stepQuestionAndAnswer, stepQuestionAndAnswerIndex
            ) in stepQuestionsAndAnswers"
        >
            <v-stepper-content
                :step="stepQuestionAndAnswerIndex + 1"
                :key="`${stepQuestionAndAnswerIndex + 1}-title`"
            >
                <div>
                    <p
                        class="font-weight-black px-4"
                        style="font-size: 1.75rem"
                    >
                        {{ getQuestion(stepQuestionAndAnswer) }}
                    </p>
                    <p
                        class="subtitle-2 #757575--text ma-0 px-4"
                        style="font-weight: 600 !important"
                    >
                        {{ getAddition(stepQuestionAndAnswer) }}
                    </p>
                    <v-text-field
                        class="pa-4"
                        v-if="step !== 3"
                        v-model="stepQuestionAndAnswer.answer"
                        maxlength="64"
                        counter="64"
                        :hint="stepQuestionAndAnswer.hint"
                        :label="stepQuestionAndAnswer.label"
                        clearable
                    ></v-text-field>
                    <calender-form
                        v-if="stepQuestionAndAnswerIndex + 1 === 3"
                        :date="stepQuestionsAndAnswers[2].answer"
                        @onClickSave="updateDate"
                        @onClickRemove="resetDate"
                    ></calender-form>
                </div>
                <div class="d-flex">
                    <v-btn text v-if="step !== 1" @click="prevStep(step)">
                        戻る
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn
                        class="d-flex flex-end"
                        color="primary"
                        :loading="loading"
                        :disabled="!stepQuestionAndAnswer.answer"
                        @click="
                            step === 3
                                ? finishedOnboarding(date)
                                : nextStep(step)
                        "
                        text
                    >
                        {{ step === 3 ? "完了" : "次へ" }}
                    </v-btn>
                </div>
            </v-stepper-content>
        </template>
    </div>
</template>
<script>
import CalenderForm from "../../../Common/Parts/Molecules/CalenderForm.vue";

export default {
    components: {
        CalenderForm,
    },
    data: () => ({
        stepQuestionsAndAnswers: [
            {
                question:
                    "さんが現在取り組んでいることまたはこれから取り組みたいことについて教えてください",
                addition:
                    "仕事や普段の生活などで頑張りたいことや習慣にしたいこと、改善したいことでも大丈夫です。",
                answer: null,
                hint: "例: 試験勉強 etc",
                label: null,
            },
            {
                question: "で達成したいゴールまたは目標は何ですか？",
                additionPrev:
                    "ゴールは後で変更や追加したりすることができます。気軽に",
                additionNext: "の理想を思い浮かべて書いてみましょう。",
                answer: null,
                hint: "例: 試験に合格する etc",
                label: "ゴール",
            },
            {
                question: "をいつまでに達成したいですか？",
                addition: "に期限を設けてみましょう。",
                answer: new Date(
                    Date.now() - new Date().getTimezoneOffset() * 60000
                )
                    .toISOString()
                    .substr(0, 10),
            },
        ],
    }),
    props: {
        user: {
            type: Object,
        },
        step: {
            type: Number,
        },
        loading: {
            type: Boolean,
        },
    },
    computed: {
        getQuestion() {
            return (stepQuestionAndAnswer) => {
                if (this.step === 1) {
                    return this.user.name + stepQuestionAndAnswer.question;
                } else if (this.step === 2) {
                    return (
                        "「" +
                        this.stepQuestionsAndAnswers[0].answer +
                        "」" +
                        stepQuestionAndAnswer.question
                    );
                } else if (this.step === 3) {
                    return (
                        "「" +
                        this.stepQuestionsAndAnswers[1].answer +
                        "」" +
                        stepQuestionAndAnswer.question
                    );
                }
            };
        },
        getAddition() {
            return (stepQuestionAndAnswer) => {
                if (this.step === 1) {
                    return stepQuestionAndAnswer.addition;
                } else if (this.step === 2) {
                    return (
                        stepQuestionAndAnswer.additionPrev +
                        "「" +
                        this.stepQuestionsAndAnswers[0].answer +
                        "」" +
                        stepQuestionAndAnswer.additionNext
                    );
                } else if (this.step === 3) {
                    return (
                        "「" +
                        this.stepQuestionsAndAnswers[1].answer +
                        "」" +
                        stepQuestionAndAnswer.addition
                    );
                }
            };
        },
    },
    methods: {
        updateDate(date) {
            this.stepQuestionsAndAnswers[2].answer = date;
        },
        resetDate() {
            this.stepQuestionsAndAnswers[2].answer = new Date(
                Date.now() - new Date().getTimezoneOffset() * 60000
            )
                .toISOString()
                .substr(0, 10);
        },
        nextStep(stepQuestionAndAnswerNum) {
            this.$emit("nextStep", stepQuestionAndAnswerNum);
        },
        prevStep(stepQuestionAndAnswerNum) {
            if (stepQuestionAndAnswerNum === 2) {
                this.stepQuestionsAndAnswers[1].answer = null;
            } else if (stepQuestionAndAnswerNum === 3) {
                this.resetDate();
            }
            this.$emit("prevStep", stepQuestionAndAnswerNum);
        },
        async finishedOnboarding() {
            this.$emit("finishedOnboarding", this.stepQuestionAndAnswers);
        },
    },
    watch: {},
};
</script>
<style scoped lang="sass"></style>
