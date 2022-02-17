<template>
  <div>
    <v-card
      :tile="$vuetify.breakpoint.sm || $vuetify.breakpoint.xs"
      class="mx-auto fill-width"
      flat
      max-width="640"
    >
      <v-card-title class="text-center pa-8">
        <h4 class="fill-width">{{ accountInputForm }}</h4>
      </v-card-title>
      <v-divider> </v-divider>
      <div class="px-6 py-8">
        <div style="max-width:344px" class="mx-auto">
          <div class="pt-6">
            <div>
              <v-text-field
                v-model="name"
                v-if="accountInputForm === '会員登録'"
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
                class="fill-width caption"
                color="#FFCB00"
                depressed
                height="48px"
                tile
              >
                {{ accountInputForm }}
              </v-btn>
            </div>
            <v-divider></v-divider>
            <div class="pt-8 pb-4" v-if="accountInputForm === '会員登録'">
              <span>すでにアカウントをお持ちですか？</span>
              <p @click="accountInputForm = 'ログイン'">ログインに移動</p>
            </div>
            <div class="pt-8 pb-4" v-if="accountInputForm === 'ログイン'">
              <span>アカウントをお持ちでない方はこちらへ</span>
              <p @click="accountInputForm = '会員登録'">会員登録に移動</p>
            </div>
          </div>
        </div>
      </div>
    </v-card>
  </div>
</template>

<script>
export default {
  data: () => ({
    accountInputForm: '会員登録',
    name: null,
    email: null,
    password: null,
    passwordShow: false,
  }),
};
</script>