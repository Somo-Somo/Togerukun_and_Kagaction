<template>
    <v-card
        :tile="$vuetify.breakpoint.sm || $vuetify.breakpoint.xs"
        class="mx-auto fill-width py-4 px-12"
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
                        @setFormValue="setFormValue"
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
import { mapState, mapGetters } from "vuex";

export default {
    components: {
        SuccessAlert,
        LoginCardTitle,
        LoginForm,
        LoginCardFooter,
    },
    data: () => ({
        isLoginForm: true,
        formValue: {
            name: "",
            email: "",
            password: "",
        },
        passwordShow: false,
        completedRegister: false,
        loading: false,
        successMessage:
            "会員登録が完了しました。<br />先ほどご登録いただいたメールアドレスとパスワードをこちらでご入力ください。",
    }),
    computed: {
        ...mapState({
            apiStatus: (state) => state.auth.apiStatus,
        }),
        ...mapGetters({
            loginErrorMessages: "auth/loginErrorMessages",
            registerErrorMessages: "auth/registerErrorMessages",
        }),
        loginAndRegisterOfErrorMessages() {
            return {
                register: this.registerErrorMessages,
                login: this.loginErrorMessages,
            };
        },
    },
    methods: {
        setFormValue(newVal) {
            return (this.formValue = newVal);
        },
        switchForm() {
            this.clearError();
            this.formValue.email = "";
            this.formValue.password = "";
            this.isLoginForm = this.isLoginForm ? false : true;
        },
        submitForm() {
            this.completedRegister = false;
            this.isLoginForm ? this.login() : this.register();
        },
        async register() {
            // 共通フォームを使ってためここでDataをSET
            const registerForm = {
                name: this.formValue.name,
                email: this.formValue.email,
                password: this.formValue.password,
            };
            this.loading = true;

            await this.$store.dispatch("auth/register", registerForm);

            this.loading = false;
            if (this.apiStatus) {
                this.completedRegister = true;
                this.formValue.email = null;
                this.formValue.password = null;
                this.isLoginForm = true;
            }
        },
        async login() {
            // 共通フォームを使ってためここでDataをSET
            const loginForm = {
                email: this.formValue.email,
                password: this.formValue.password,
            };
            this.loading = true;
            await this.$store.dispatch("auth/login", loginForm);

            if (this.apiStatus) {
                const data = await this.$store.dispatch(
                    "initialize/getUserHasProjectAndTodo",
                    this.$route
                );
                this.loading = false;
                if (data.onboarding) {
                    this.$router.push("/onboarding");
                } else if (Object.keys(data.schedule).length) {
                    this.$router.push("/schedule");
                } else if (Object.keys(data.project).length) {
                    const firstProject = Object.entries(data.project)[0][1];
                    this.$store.dispatch("project/selectProject", firstProject);
                    this.$router.push("/project/" + firstProject.uuid);
                } else {
                    this.$router.push("/schedule");
                }
            }
            this.loading = false;
        },
        clearError() {
            this.$store.commit("auth/setLoginErrorMessages", null);
            this.$store.commit("auth/setRegisterErrorMessages", null);
        },
    },
    created() {
        this.clearError();
    },
};
</script>
