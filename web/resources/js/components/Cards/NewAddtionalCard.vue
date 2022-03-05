<template>
  <v-col class="px-md-0 hidden-sm-and-down" cols="12">
    <v-card class="rounded" @click="$emit('clickAditional')" outlined>
      <v-list class="py-0" style="height: 80px">
        <v-list-item
          class="d-flex justify-space-around"
          style="height: 80px"
          link
        >
          <v-icon class="pa-2">mdi-plus</v-icon>
          <v-list-item-content>
            <v-list-item-title>
              <p class="grey--text font-weight-bold ma-0 pa-2">
                {{category}}を追加する
              </p>
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-card>
  </v-col>
</template>

<script>
export default {
  props: {
    category: {
      type: String,
    },
  },
};
</script>