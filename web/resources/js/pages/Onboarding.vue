<template>
    <v-container
        class="d-flex flex-column ma-auto px-md-16"
        style="max-width: 960px; height:100%;"
        fluid
    >
        <Header
            :headerTitle="'Kagactionへようこそ'"
            v-if="$vuetify.breakpoint.mdAndUp"
        />
        <div class="my-16">
            <v-stepper v-model="step">
                <v-stepper-header style="width: 100%">
                    <template
                        class=""
                        v-for="(stepHeader, stepHeaderIndex) in stepHeaders"
                    >
                        <v-stepper-step
                            class="caption font-weight-bold"
                            :key="`${stepHeaderIndex + 1}-title`"
                            :complete="stepHeaderIndex + 1 < step"
                            :step="stepHeaderIndex + 1"
                        >
                            {{ stepHeader.title }}
                        </v-stepper-step>
                        <v-divider
                            :key="`${stepHeaderIndex + 1}-divider`"
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
                            :key="`${stepQuestionAndAnswerIndex + 1}-content`"
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
                                <v-menu
                                    v-if="stepQuestionAndAnswerIndex + 1 === 3"
                                    ref="calenderMenu"
                                    v-model="calenderMenu"
                                    :close-on-content-click="false"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="auto"
                                >
                                    <template
                                        class="d-flex"
                                        v-slot:activator="{ on, attrs }"
                                    >
                                        <v-text-field
                                            class="d-flex align-self-center ma-0 pt-5 px-4"
                                            v-model="date"
                                            :prepend-icon="'mdi-calendar'"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        >
                                        </v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-if="stepQuestionAndAnswerIndex + 1  === 3"
                                        v-model="date"
                                        no-title
                                        scrollable
                                    >
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="calenderMenu = false"
                                        >
                                            キャンセル
                                        </v-btn>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="onClickDateSave()"
                                        >
                                            保存
                                        </v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </div>
                            <div class="d-flex">
                                <v-btn
                                    text
                                    v-if="step !== 1"
                                    @click="
                                        prevStep(stepQuestionAndAnswerIndex + 1)
                                    "
                                >
                                    戻る
                                </v-btn>
                                <v-spacer></v-spacer>
                                <v-btn
                                    v-if="step !== 3"
                                    class="d-flex flex-end"
                                    color="primary"
                                    @click="
                                        nextStep(stepQuestionAndAnswerIndex + 1)
                                    "
                                    :disabled="!stepQuestionAndAnswer.answer"
                                    text
                                >
                                    次へ
                                </v-btn>
                                <v-btn
                                    v-if="step === 3"
                                    class="d-flex flex-end"
                                    color="primary"
                                    @click="finishedOnboarding()"
                                    :disabled="!stepQuestionAndAnswer.answer"
                                    text
                                >
                                    完了
                                </v-btn>
                            </div>
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
                question:
                    "さんが現在取り組んでいることまたはこれから取り組みたいことについて教えてください",
                addition:
                    "仕事や普段の生活などで頑張りたいことや習慣にしたいこと、改善したいことでも大丈夫です。",
                answer: null,
                hint: "例: アプリ開発, 試験勉強 etc",
                label: null,
            },
            {
                question: "で達成したいゴールまたは目標は何ですか？",
                additionPrev:
                    "ゴールは後で変更や追加したりすることができます。気軽に",
                additionNext: "の理想を思い浮かべて書いてみましょう。",
                answer: null,
                hint: "例: DAU〇〇人, 試験に合格する etc",
                label: "ゴール",
            },
            {
                question: "をいつまでに達成したいですか？",
                addition: "の期限を設けてみましょう。",
                answer: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
            },
        ],
        calenderMenu: false,
        date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
    }),
    computed: {
        ...mapGetters({
            user: "auth/user",
            onboarding: "onboarding/onboarding",
        }),
        getQuestion() {
            return (stepQuestionAndAnswer) => {
                if (this.step === 1) {
                    return this.user.name + stepQuestionAndAnswer.question;
                } else if (this.step === 2) {
                    return (
                        "「" +
                        this.user.name +
                        "」" +
                        stepQuestionAndAnswer.question
                    );
                } else if (this.step === 3) {
                    return (
                        "「" +
                        this.user.name +
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
                        this.user.name +
                        "」" +
                        stepQuestionAndAnswer.additionNext
                    );
                } else if (this.step === 3) {
                    return (
                        "「" +
                        this.user.name +
                        "」" +
                        stepQuestionAndAnswer.addition
                    );
                }
            };
        },
    },
    methods: {
        nextStep(stepQuestionAndAnswerNum) {
            return (this.step =
                stepQuestionAndAnswerNum === this.steps
                    ? 1
                    : stepQuestionAndAnswerNum + 1);
        },
        prevStep(stepQuestionAndAnswerNum) {
            if (stepQuestionAndAnswerNum === 2) {
                this.stepQuestionsAndAnswers[stepQuestionAndAnswerNum - 1].answer = null;
            } else if (stepQuestionAndAnswerNum === 3) {
                this.stepQuestionsAndAnswers[stepQuestionAndAnswerNum - 1].answer = (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10);
            }
            return (this.step = stepQuestionAndAnswerNum - 1);
        },
        onClickDateSave() {
            this.calenderMenu = false;
            // カレンダーの値をリセット
            this.stepQuestionsAndAnswers[2].answer = this.date;
        },
        async finishedOnboarding() {
            const projectName = {name: this.stepQuestionsAndAnswers[0].answer};
            const project = await this.$store.dispatch("project/createProject", projectName);
            const goal = await this.$store.dispatch("todo/createGoal", {
                project: project,
                todoName: this.stepQuestionsAndAnswers[1].answer,
            });
            const response = await this.$store.dispatch("todo/updateDate",{ 
                date:this.stepQuestionsAndAnswers[2].answer,
                todo:goal, 
                project:project,
            });
            await this.$store.dispatch("onboarding/finishedOnboarding", response);
            this.$router.push("/project/" + project.uuid);
        },
    },
  watch: {
    onboarding(val, old) {
      if (!val) {
        this.$router.push('/schedule');
      }
    },
  },
};
</script>
<style scoped lang="sass">
.v-stepper__header
  box-shadow: none !important
</style>
