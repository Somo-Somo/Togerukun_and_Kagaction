<template>
  <v-btn @click="logout">ログアウト</v-btn>
</template>

<script>
export default {
  methods: {
    logout() {
          this.$store.dispatch("auth/logout")
            .then(() => {
              this.$router.push("/login");
            })
            .catch((error) => {
              this.$router.push("/abc");
            });
    },
  },
};
</script>