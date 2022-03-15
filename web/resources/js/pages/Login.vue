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
        <h4 class="fill-width">{{ isLoginForm ? "ログイン" : "会員登録" }}</h4>
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
                depressed
                height="48px"
                tile
              >
                {{ isLoginForm ? "ログイン" : "会員登録" }}
              </v-btn>
            </div>
            <v-divider></v-divider>

            <div class="my-4" v-if="!isLoginForm">
              <span>すでにアカウントをお持ちですか？</span>
              <p
                @click="
                  isLoginForm = true;
                  email = '';
                  password = '';
                  clearError()
                "
              >
                ログインに移動
              </p>
            </div>
            <div class="pt-8 pb-4" v-if="isLoginForm">
              <span>アカウントをお持ちでない方はこちらへ</span>
              <p
                @click="
                  isLoginForm = false;
                  email = '';
                  password = '';
                  clearError()
                "
              >
                会員登録に移動
              </p>
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
      return this.isLoginForm ?  this.loginErrorMessages.email[0] : this.registerErrorMessages.email[0];
    },
    errorMessagesPassword(){
      return this.isLoginForm ? this.loginErrorMessages.password[0] : this.registerErrorMessages.password[0];
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

      await this.$store.dispatch("auth/register", this.registerForm);

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
      await this.$store.dispatch("auth/login", this.loginForm);

      if (this.apiStatus) {
        await this.$store.dispatch("initialize/getUserHasProjectAndHypothesis", this.$route);
        this.$router.push("/projects");
      }
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