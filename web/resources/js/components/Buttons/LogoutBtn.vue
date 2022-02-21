<template>
  <v-btn @click="logout">ログアウト</v-btn>
</template>

<script>
export default {
  methods: {
    async logout() {
      await this.$store.dispatch("auth/logout");
      console.info("loginへ移動");
      await this.$router.push("/login");
    },
  },
};
</script>