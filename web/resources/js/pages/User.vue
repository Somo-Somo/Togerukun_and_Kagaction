<template>
  <div>
    <span v-if="isLogin" class="navbar__item">
       <p> 名前: {{ username }}</p>
    </span>
    <p>Success</p>
    <LogoutBtn />
  </div>
</template>

<script>
import LogoutBtn from "../components/Buttons/LogoutBtn.vue";

export default {
  components: {
    LogoutBtn,
  },
  computed: {
    isLogin () {
      return this.$store.getters['auth/check']
    },
    username () {
      return this.$store.getters['auth/username']
    }
  }
};
</script>
