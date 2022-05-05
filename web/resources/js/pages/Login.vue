<template>
  <div>
    <v-card
      :tile="$vuetify.breakpoint.sm || $vuetify.breakpoint.xs"
      class="mx-auto fill-width"
      flat
      max-width="720"
    >   
      <v-alert
        v-if="completedRegister"
        class="my-2"
        outlined
        type="success"
        text
      >
      会員登録が完了しました。<br>先ほどご登録いただいたメールアドレスとパスワードをこちらでご入力ください。
    </v-alert>
      <v-card-title class="text-center pa-8">
        <h4 class="fill-width">
          Kagaction(トライアル版)&nbsp;{{ isLoginForm ? "ログイン" : "会員登録" }}
        </h4>
      </v-card-title>
      <v-divider> </v-divider>
      <form class="form" @submit.prevent="submitForm()">
        <div class="px-6 py-8">
          <div style="max-width: 344px" class="mx-auto">
            <div class="pt-6">
              <v-text-field
                v-model="registerForm.name"
                v-if="!isLoginForm"
                :error-messages="registerErrorMessages ? registerErrorMessages.name : null"
                autofocus
                dense
                height="48px"
                outlined
                placeholder="ユーザ名"
              ></v-text-field>

              <v-text-field
                v-model="email"
                :error-messages="loginErrorMessages || registerErrorMessages ? errorMessagesEmail : null"
                autofocus
                dense
                height="48px"
                outlined
                placeholder="メールアドレス"
              ></v-text-field>

              <v-text-field
                v-model="password"
                :append-icon="passwordShow ? 'mdi-eye' : 'mdi-eye-off'"
                :type="passwordShow ? 'text' : 'password'"
                :error-messages="loginErrorMessages || registerErrorMessages ? errorMessagesPassword : null"
                dense
                height="48px"
                name="input-password"
                outlined
                placeholder="パスワード"
                @click:append="passwordShow = !passwordShow"
              ></v-text-field>
            <div 
              v-if="!isLoginForm" 
              style="position: relative;" 
              :style="!registerErrorMessages ? 'top: -16px;': 'top: -8px;'">
              <ul>
                <li>半角英数字・記号8文字以上</li>
                <li>半角英字・数字それぞれ一文字以上含む</li>
              </ul>
            </div>
            </div>
            <div class="login-btn mt-2 mb-8">
              <v-btn
                type="submit"
                class="fill-width caption"
                color="#FFCB00"
                height="48px"
                :loading="loading"
                :disabled="loading"
                depressed
                tile
              >
                {{ isLoginForm ? "ログイン" : "会員登録" }}
              </v-btn>
            </div>
            <v-divider></v-divider>

            <div class="my-4" v-if="!isLoginForm">
              <span>すでにアカウントをお持ちですか？</span>
              <v-btn
                class="my-2"
                @click="
                  isLoginForm = true;
                  email = '';
                  password = '';
                  clearError()
                  text
                  small
                "
              >
                ログインに移動
              </v-btn>
            </div>
            <div class="my-4" v-if="isLoginForm">
              <span>アカウントをお持ちでない方はこちらへ</span>
              <v-btn
                class="my-2"
                @click="
                  isLoginForm = false;
                  email = '';
                  password = '';
                  clearError()
                  text
                  small
                "
              >
                会員登録に移動
              </v-btn>
            </div>
          </div>
        </div>
      </form>
    </v-card>
  </div>
</template>

<script>
import { mapState,mapGetters } from "vuex";

export default {
  data: () => ({
    isLoginForm: true,
    email: "",
    password: "",
    loginForm: {
      email: "",
      password: "",
    },
    registerForm: {
      name: "",
      email: "",
      password: "",
    },
    passwordShow: false,
    completedRegister: false,
    loading: false,
  }),
  computed: {
    ...mapState({
      apiStatus: (state) => state.auth.apiStatus,
    }),
    ...mapGetters({
      loginErrorMessages: 'auth/loginErrorMessages',
      registerErrorMessages: 'auth/registerErrorMessages'
    }),
    errorMessagesEmail(){
      const errorMessages = this.isLoginForm ?  this.loginErrorMessages : this.registerErrorMessages;
      return errorMessages.email ? errorMessages.email[0] : null;
    },
    errorMessagesPassword(){
      const errorMessages = this.isLoginForm ? this.loginErrorMessages : this.registerErrorMessages;
      return errorMessages.password ? errorMessages.password[0] : null;
    },
  },
  methods: {
    submitForm() {
      this.completedRegister = false;
      this.isLoginForm ? this.login() : this.register();
    },
    async register() {
      // 共通フォームを使ってためここでDataをSET
      this.registerForm.email = this.email;
      this.registerForm.password = this.password;
      this.loading = true;

      await this.$store.dispatch("auth/register", this.registerForm);
      
      this.loading = false;
      if (this.apiStatus) {
        this.completedRegister = true;
        this.email = null;
        this.password = null;
        this.registerForm.name = null;
        this.isLoginForm = true;
      }
    },
    async login() {
      // 共通フォームを使ってためここでDataをSET
      this.loginForm.email = this.email;
      this.loginForm.password = this.password;
      this.loading = true;
      await this.$store.dispatch("auth/login", this.loginForm);

      if (this.apiStatus) {
        const data = await this.$store.dispatch("initialize/getUserHasProjectAndHypothesis", this.$route);
        this.loading = false;
        if (Object.keys(data.schedule).length) {
          this.$router.push("/schedule");
        } else if (Object.keys(data.project).length) {
          const firstProject = Object.entries(data.project)[0][1];
          this.$store.dispatch("project/selectProject", firstProject);
          this.$router.push("/project/" + firstProject.uuid );
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