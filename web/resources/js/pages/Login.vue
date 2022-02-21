<template>
  <div>
    <v-card
      :tile="$vuetify.breakpoint.sm || $vuetify.breakpoint.xs"
      class="mx-auto fill-width"
      flat
      max-width="640"
    >
      <v-card-title class="text-center pa-8">
        <h4 class="fill-width">{{ isLoginForm ? "ログイン" : "会員登録" }}</h4>
      </v-card-title>
      <v-divider> </v-divider>
      <form class="form" @submit.prevent="submitForm()">
          <div v-if="loginErrors" class="errors">
    <ul v-if="loginErrors.email">
      <li v-for="msg in loginErrors.email" :key="msg">{{ msg }}</li>
    </ul>
    <ul v-if="loginErrors.password">
      <li v-for="msg in loginErrors.password" :key="msg">{{ msg }}</li>
    </ul>
  </div>
        <div class="px-6 py-8">
          <div style="max-width: 344px" class="mx-auto">
            <div class="pt-6">
              <v-text-field
                v-model="registerForm.name"
                v-if="!isLoginForm"
                autofocus
                dense
                height="48px"
                outlined
                placeholder="ユーザ名"
              ></v-text-field>

              <v-text-field
                v-model="email"
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
                dense
                height="48px"
                name="input-password"
                outlined
                placeholder="パスワード"
                @click:append="passwordShow = !passwordShow"
              ></v-text-field>
            </div>
            <div class="login-btn pb-8">
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
            <div class="pt-8 pb-4" v-if="!isLoginForm">
              <span>すでにアカウントをお持ちですか？</span>
              <p
                @click="
                  isLoginForm = true;
                  email = '';
                  password = '';
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
import { mapState } from 'vuex'

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
  }),
  computed: {
    ...mapState({
      apiStatus: state => state.auth.apiStatus,
      loginErrors: state => state.auth.loginErrorMessages
    })
  },
  methods: {
    submitForm() {
      this.isLoginForm ? this.login() : this.register();
    },
    async login() {
      // 共通フォームを使ってためここでDataをSET
      this.loginForm.email = this.email;
      this.loginForm.password = this.password;
      await this.$store.dispatch("auth/login", this.loginForm)

      if (this.apiStatus) {
        this.$router.push('/user_test')
      }  
    },
    register() {
      // 共通フォームを使ってためここでDataをSET
      this.registerForm.email = this.email;
      this.registerForm.password = this.password;

      this.$store.dispatch("auth/register", this.registerForm)
            .then(() => {
              this.$router.push("/user_test");
            })
            .catch((error) => {
              this.$router.push("/login");
            });
    },
    clearError () {
      this.$store.commit('auth/setLoginErrorMessages', null)
    }
  },
  created () {
    this.clearError()
  }
};
</script>