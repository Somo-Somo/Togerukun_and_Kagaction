<template>
  <v-btn @click="logout">ログアウト</v-btn>
</template>

<script>
import { mapState, mapGetters } from 'vuex'

export default {
  computed: {
    ...mapState({
      apiStatus: state => state.auth.apiStatus
    }),
    ...mapGetters({
      isLogin: 'auth/check'
    })
  },
  methods: {
    async logout() {
      await this.$store.dispatch("auth/logout");
      console.info("loginへ移動");
      if (this.apiStatus) {
        this.$router.push('/login')
      }
    },
  },
};
</script>