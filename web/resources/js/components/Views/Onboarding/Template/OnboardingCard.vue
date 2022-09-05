<template>
    <div class="my-16">
        <v-stepper v-model="step">
            <onboarding-step-header :step="step"></onboarding-step-header>
            <v-divider></v-divider>
            <v-stepper-items>
                <onboarding-main-content
                    :user="user"
                    :step="step"
                    :loading="loading"
                    @nextStep="nextStep"
                    @prevStep="prevStep"
                    @finishedOnboarding="finishedOnboarding"
                ></onboarding-main-content>
            </v-stepper-items>
        </v-stepper>
    </div>
</template>

<script>
import OnboardingStepHeader from "../Parts/OnboardingStepHeader.vue";
import OnboardingMainContent from "../Parts/OnboardingMainContent.vue";

export default {
    components: {
        OnboardingStepHeader,
        OnboardingMainContent,
    },
    data: () => ({}),
    props: {
        user: {
            type: Object,
        },
        step: {
            type: Number,
        },
        loading: { type: Boolean },
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
