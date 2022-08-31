<template>
    <div class="pa-16" style="height: 100%">
        <login-card
            :isLoginForm="isLoginForm"
            :formValue="formValue"
            :completedRedister="completedRedister"
            :loading="loading"
            :loginAndRegisterOfErrorMessages="loginAndRegisterOfErrorMessages"
            @setFormValue="setFormValue"
            @switchForm="switchForm"
            @submitForm="submitForm"
        ></login-card>
    </div>
</template>

<script>
import LoginCard from "./Template/LoginCard.vue";
import { mapState, mapGetters } from "vuex";

export default {
    components: {
        LoginCard,
    },
    data: () => ({
        isLoginForm: true,
        formValue: {
            name: "",
            email: "",
            password: "",
        },
        completedRegister: false,
        loading: false,
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
