<template>
  <v-card ref="form">
    <v-card-text class="d-flex align-content-center px-9 pt-9 pb-0">
      <v-text-field
        class="pa-0 ma-0"
        v-model="message1"
        label="プロジェクト名"
        clearable
      ></v-text-field>
    </v-card-text>
    <v-divider></v-divider>
    <v-card-actions>
      <v-btn @click="$emit('clickCancel')" text> Cancel </v-btn>
      <v-spacer></v-spacer>
      <v-slide-x-reverse-transition> </v-slide-x-reverse-transition>
      <v-btn color="primary" @click="$emit('clickNext')" text> Next </v-btn>
    </v-card-actions>
  </v-card>
</template>


<script>
export default {

};
</script>