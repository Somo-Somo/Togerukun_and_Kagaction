<template>
  <v-hover v-slot="{ hover }">
    <div class="d-flex d-md-none pa-6">
      <v-avatar
        class="d-flex justify-center"
        size="64"
        color="white"
        style="position: fixed; right: 24px; bottom: 64px; z-index: 10"
      >
        <v-avatar
          size="48"
          :class="{ 'show-plus': hover }"
          :color="transparent.plus"
          @click="$emit('clickAditional')"
        >
          <v-icon size="24" color="white" style="backgroud-color: white">
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
    tab: {
      type: Number,
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