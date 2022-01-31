<template>
  <div class="d-flex d-md-none  justify-center pa-6">
        <v-avatar
          size="80"
          color="white"
          style="position: relative; bottom: -24px; z-index: 10"
        >
          <v-avatar size="64" color="#03A9F4" v-bind="attrs" v-on="on">
            <v-icon size="32" color="white" style="backgroud-color: white">
              mdi-plus
            </v-icon>
          </v-avatar>
        </v-avatar>
      </div>
</template>

<script>
export default {
  props: {
    on: {
      type: Boolean,
    },
    attrs: {
      type: Boolean,
    },
  },
};
</script>