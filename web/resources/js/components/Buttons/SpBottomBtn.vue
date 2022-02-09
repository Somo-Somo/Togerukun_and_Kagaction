<template>
  <v-hover v-slot="{ hover }">
    <div class="d-flex d-md-none pa-6">
      <v-avatar
        v-if="$route.matched[0].path !== '/project'"
        class="d-flex justify-center"
        size="48"
        color="white"
        style="position: fixed; left: 16px; bottom: 72px; z-index: 10"
      >
        <v-avatar
          class="ma-3"
          size="64"
          @click="toBack(headerTitle)"
          :class="{ 'show-chevron-left': hover }"
          :color="transparent.chevronLeft"
          v-bind="attrs"
        >
          <v-icon size="32" color="white" style="backgroud-color: white">
            mdi-chevron-left
          </v-icon>
        </v-avatar>
      </v-avatar>
      <v-avatar
        v-if="tab !== 3"
        class="d-flex justify-center"
        size="80"
        color="white"
        style="position: fixed; right: 24px; bottom: 24px; z-index: 10"
      >
        <v-avatar
          size="64"
          :class="{ 'show-plus': hover }"
          :color="transparent.plus"
          v-bind="attrs"
          v-on="on"
        >
          <v-icon size="32" color="white" style="backgroud-color: white">
            mdi-plus
          </v-icon>
        </v-avatar>
      </v-avatar>
    </div>
  </v-hover>
</template>

<script>
export default {
  data: () => ({
    transparent: {
      chevronLeft: "rgba(128, 128, 128, 0.6)",
      plus: "rgba(3, 169, 244, 0.6)",
    },
  }),
  props: {
    on: {
      type: Boolean,
    },
    attrs: {
      type: Boolean,
    },
    tab: {
      type: Number,
    },
    headerTitle: {
      type: String,
    },
  },
  methods: {
    toBack: function (headerTitle) {
      return headerTitle === "仮説一覧"
        ? this.$router.push({ path: "/project" })
        : this.$router.push({ path: "/project/123" });
    },
  },
};
</script>

<style scoped lang='sass'>
.show-chevron-left
  color: rgba(128, 128, 128, 1) !important

.show-plus
  color: rgba(128, 128, 128, 1) !important
</style>