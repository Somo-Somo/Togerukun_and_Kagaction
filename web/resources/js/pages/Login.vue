<template>
  <div>
    <v-card
      :tile="$vuetify.breakpoint.sm || $vuetify.breakpoint.xs"
      class="mx-auto fill-width"
      flat
      max-width="640"
    >
      <v-card-title class="text-center pa-8">
        <h4 class="fill-width">{{ isLoginForm ? 'ログイン' : '会員登録' }}</h4>
      </v-card-title>
      <v-divider> </v-divider>
      <form class="form" @submit="submitForm">
        <div class="px-6 py-8">
          <div style="max-width: 344px" class="mx-auto">
            <div class="pt-6">
              <div>
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
                  :model="isLoginForm ? loginForm.email : registerForm.email"
                  autofocus
                  dense
                  height="48px"
                  outlined
                  placeholder="メールアドレス"
                ></v-text-field>

                <v-text-field
                  :model="isLoginForm ? loginForm.password : registerForm.password"
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
                  class="fill-width caption"
                  color="#FFCB00"
                  depressed
                  height="48px"
                  tile
                >
                  {{ isLoginForm ? 'ログイン' : '会員登録'}}
                </v-btn>
              </div>
              <v-divider></v-divider>
              <div class="pt-8 pb-4" v-if="!isLoginForm">
                <span>すでにアカウントをお持ちですか？</span>
                <p @click="isLoginForm = true">ログインに移動</p>
              </div>
              <div class="pt-8 pb-4" v-if="isLoginForm">
                <span>アカウントをお持ちでない方はこちらへ</span>
                <p @click="isLoginForm = false">会員登録に移動</p>
              </div>
            </div>
          </div>
        </div>
      </form>
    </v-card>
  </div>
</template>

<script>
export default {
  data: () => ({
    isLoginForm: true,
    loginForm: {
        email: '',
        password: ''
    },
    registerForm: {
        name: '',
        email: '',
        password: '',
    },
    passwordShow: false,
  }),
  methods: {
    submitForm() {
      this.isLoginForm ? this.login() : this.register();
    },
    async login () {
      // authストアのloginアクションを呼び出す
      await this.$store.dispatch('auth/login', this.loginForm)

      if (this.apiStatus) {
        // トップページに移動する
        this.$router.push('/')
      }
    },
    async register () {
      // authストアのresigterアクションを呼び出す
      await this.$store.dispatch('auth/register', this.registerForm)

      if (this.apiStatus) {
        // トップページに移動する
        this.$router.push('/')
      }
    },
  },
};
</script>