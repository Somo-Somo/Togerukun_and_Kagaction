<template>
    <div class="my-16">
        <v-stepper v-model="step">
            <onboarding-step-header :step="step"></onboarding-step-header>
            <v-divider></v-divider>
            <v-stepper-items>
                <onboarding-main-content
                    :step="step"
                    :user="user"
                    @nextStep="nextStep"
                    @prevStep="prevStep"
                    @finishedOnboarding="finishedOnboarding"
                ></onboarding-main-content>
            </v-stepper-items>
        </v-stepper>
    </div>
</template>

<script>
import TodoHeader from "../../Common/Parts/Organisms/TodoHeader.vue";
import OnboardingStepHeader from "../Parts/OnboardingStepHeader.vue";
import OnboardingMainContent from "../Parts/OnboardingMainContent.vue";

export default {
    components: {
        TodoHeader,
        OnboardingStepHeader,
        OnboardingMainContent,
    },
    data: () => ({}),
    props: {
        step: {
            type: Number,
        },
    },
    computed: {},
    methods: {
        nextStep(stepQuestionAndAnswerNum) {
            this.$emit("nextStep", stepQuestionAndAnswerNum);
        },
        prevStep(stepQuestionAndAnswerNum) {
            this.$emit("prevStep", stepQuestionAndAnswerNum);
        },
        async finishedOnboarding(stepQuestionAndAnswers) {
            this.$emit("finishedOnboarding", stepQuestionAndAnswers);
        },
    },
    watch: {},
};
</script>
<style scoped lang="sass">
.v-stepper__header
  box-shadow: none !important
</style>
