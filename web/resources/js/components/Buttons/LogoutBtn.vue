<template>
  <v-btn @click="logout">ログアウト</v-btn>
</template>

<script>
export default {
  methods: {
    logout() {
      axios
        .get("/sanctum/csrf-cookie", { withCredentials: true })
        .then((response) => {
          this.$store
            .dispatch("auth/logout")
            .then(() => {
              this.$router.push("/login");
            })
            .catch((error) => {
              this.$router.push("/projects");
              console.log(error);
            });
        });
    },
  },
};
</script>