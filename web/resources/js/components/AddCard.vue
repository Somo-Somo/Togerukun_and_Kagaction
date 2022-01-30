<template>
  <div class="text-center">
    <v-dialog v-model="dialog" width="500">
      <!-- 追加するボタン -->
      <template v-slot:activator="{ on, attrs }">
        <v-col class="px-md-0" cols="12">
          <v-card class="rounded" v-bind="attrs" v-on="on" outlined>
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
                      プロジェクトを追加する
                    </p>
                  </v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-card>
        </v-col>
      </template>
      <!-- 追加のフォーム -->
      <v-card ref="form">
        <v-card-text class="d-flex align-content-center px-9 pt-9 pb-3">
          <v-text-field
            class="pa-0 ma-0"
            v-model="message1"
            label="プロジェクト名"
            clearable
          ></v-text-field>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-btn @click="dialog = false" text> Cancel </v-btn>
          <v-spacer></v-spacer>
          <v-slide-x-reverse-transition> </v-slide-x-reverse-transition>
          <v-btn color="primary" @click="dialog = false" text> Submit </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      dialog: false,
    };
  },
};
</script>