<template>
    <v-card
        :tile="$vuetify.breakpoint.sm || $vuetify.breakpoint.xs"
        class="align-self-center ma-auto fill-width py-4 px-12"
        flat
        max-width="480"
        min-height="560"
        elevation="2"
    >
        <success-alert
            v-if="completedRegister"
            :message="successMessage"
        ></success-alert>
        <login-card-title
            :title="isLoginForm ? 'ログイン画面' : '会員登録画面'"
        ></login-card-title>
        <v-divider> </v-divider>
        <form class="form" @submit.prevent="submitForm()">
            <div class="px-6 py-8">
                <div style="max-width: 344px" class="mx-auto">
                    <login-form
                        :isLoginForm="isLoginForm"
                        :loginAndRegisterOfErrorMessages="
                            loginAndRegisterOfErrorMessages
                        "
                        :formValue="formValue"
                    ></login-form>
                    <div class="login-btn mt-2 mb-8">
                        <v-btn
                            type="submit"
                            class="font-weight-bold fill-width caption"
                            color="info"
                            height="48px"
                            style="width: 100%"
                            :loading="loading"
                            :disabled="loading"
                            depressed
                        >
                            {{ isLoginForm ? "ログイン" : "会員登録" }}
                        </v-btn>
                    </div>
                    <v-divider></v-divider>
                    <login-card-footer
                        :isLoginForm="isLoginForm"
                        @switchForm="switchForm"
                    ></login-card-footer>
                </div>
            </div>
        </form>
    </v-card>
</template>

<script>
import SuccessAlert from "../../../Common/Parts/Atom/SuccessAlert.vue";
import LoginCardTitle from "../Parts/LoginCardTitle.vue";
import LoginForm from "../Parts/LoginForm.vue";
import LoginCardFooter from "../Parts/LoginCardFooter.vue";

export default {
    components: {
        SuccessAlert,
        LoginCardTitle,
        LoginForm,
        LoginCardFooter,
    },
    data: () => ({
        successMessage:
            "会員登録が完了しました。先ほどご登録いただいたメールアドレスとパスワードをこちらでご入力ください。",
    }),
    props: {
        isLoginForm: {
            type: Boolean,
        },
        formValue: {
            type: Object,
        },
        completedRegister: {
            type: Boolean,
        },
        loading: {
            type: Boolean,
        },
        loginAndRegisterOfErrorMessages: {
            type: Object,
        },
    },
    computed: {},
    methods: {
        switchForm() {
            this.$emit("switchForm");
        },
        submitForm() {
            this.$emit("submitForm");
        },
    },
};
</script>
