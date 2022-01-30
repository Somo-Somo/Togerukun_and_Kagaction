<template>
  <div class="text-center">
    <v-dialog v-model="cardForm" width="500">
      <template v-slot:activator="{ on, attrs }">
         <!-- PC版追加ボタン -->
        <v-col class="px-md-0 hidden-sm-and-down" cols="12">
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
        <!-- スマホ版追加ボタン -->
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
      <!-- 追加のフォーム -->
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
          <v-btn @click="cardForm = false" text> Cancel </v-btn>
          <v-spacer></v-spacer>
          <v-slide-x-reverse-transition> </v-slide-x-reverse-transition>
          <v-btn color="primary" @click="cardForm = false" text> Next </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: {
    cardForm: {
      type: Boolean,
    },
  },
};
</script>