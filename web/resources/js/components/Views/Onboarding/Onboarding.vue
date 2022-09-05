<template>
    <v-container
        class="d-flex flex-column ma-auto px-md-16"
        style="max-width: 960px; height: 100%"
        fluid
    >
        <todo-header
            :headerTitle="'Kagactionへようこそ'"
            v-if="$vuetify.breakpoint.mdAndUp"
        ></todo-header>
        <div class="my-16">
            <onboarding-card
                :user="user"
                :step="step"
                :loading="loading"
                @prevStep="prevStep"
                @nextStep="nextStep"
                @finishedOnboarding="finishedOnboarding"
            ></onboarding-card>
        </div>
    </v-container>
</template>

<script>
import TodoHeader from "../../Common/Parts/Organisms/TodoHeader.vue";
import OnboardingCard from "./Template/OnboardingCard.vue";
import { mapGetters } from "vuex";

export default {
    components: {
        TodoHeader,
        OnboardingCard,
    },
    data: () => ({
        step: 1,
        steps: 3,
        loading: false,
    }),
    computed: {
        ...mapGetters({
            user: "auth/user",
            onboarding: "onboarding/onboarding",
        }),
    },
    methods: {
        nextStep(stepQuestionAndAnswerNum) {
            return (this.step =
                stepQuestionAndAnswerNum === this.steps
                    ? 1
                    : stepQuestionAndAnswerNum + 1);
        },
        prevStep(stepQuestionAndAnswerNum) {
            return (this.step = stepQuestionAndAnswerNum - 1);
        },
        async finishedOnboarding(stepQuestionsAndAnswers) {
            this.loading = true;
            await this.$store.dispatch(
                "onboarding/finishedOnboarding",
                stepQuestionsAndAnswers
            );
            const initialize = await this.$store.dispatch(
                "initialize/getUserHasProjectAndTodo",
                this.$route
            );
            const firstProject = Object.entries(initialize.project)[0][1];
            await this.$store.dispatch("project/selectProject", firstProject);
            this.$router.push("/project/" + firstProject.uuid);
            this.loading = false;
        },
    },
    watch: {
        onboarding(val, old) {
            // オンボーディング完了後のプロジェクト移動前にこのwatchが発動してしまうので
            // ローディング中は予定に遷移しないようにする
            if (!val && !this.loading) {
                this.$router.push("/schedule");
            }
        },
    },
};
</script>
<style scoped lang="sass"></style>
