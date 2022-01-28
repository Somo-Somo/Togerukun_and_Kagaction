<template>
    <v-card elevation="2">
      <v-list style="height:80px">
          <v-list-item style="height:64px">
            <v-list-item-content>
              <v-list-item-title><p class="font-weight-black ma-0">Message</p></v-list-item-title>
            </v-list-item-content>
          </v-list-item>
      </v-list>
    </v-card>
</template>

<script>
export default {
  props: {
    card: {
      type: String,
    }
  }
}
</script>