<template>
  <v-container
    class="d-flex flex-column py-6 my-md-2 px-md-16"
    style="max-width: 900px"
    fluid
  >
  <Header :headerTitle="'現在の目標'"/>
      <template>
        <div
          class="d-flex justify-space-between"
          style="position: fixed; width: 772px"
          absolute
        >
          <v-subheader class="pa-md-0 d-flex" style="font-size: 1rem">
            <p class="ma-0 font-weight-bold" color="grey darken-1">
              現在の目標一覧 
            </p>
          </v-subheader>
        </div>
        <div
          class="overflow-y-auto d-flex flex-column"
          :class="$vuetify.breakpoint.mdAndUp ? 'cardStyle' : 'spCardStyle'"
        >
          <v-progress-circular
            class="mx-auto my-8"
            v-if="loading"
            color="grey lighten-1"
            indeterminate
          ></v-progress-circular>

        <div class="my-4" v-show="!currentGoalList.length && !loading">
            <p class="grey--text font-weight-bold ma-0 py-2">
                現在の目標はありません
            </p>
        </div>
        </div>
      </template>
  </v-container>
</template>

<script>
import Header from "../components/Header.vue";
import { mapGetters } from 'vuex';

export default {
  components: {
    Header,
  },
  data: () => ({
    category : "現在の目標",
  }),
  computed: {
    ...mapGetters({
      loading: 'initialize/loading',
      currentGoalList: 'hypothesis/currentGoalList',
    }),
  }
};
</script>

<style scoped lang='sass'>
.cardStyle
  height: calc(100vh - 152px)
  position: relative
  top: 48px

.spCardStyle
  height: calc(100vh - 224px)
  position: relative
</style>