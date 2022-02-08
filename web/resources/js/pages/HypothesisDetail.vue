<template>
  <v-container
    class="d-flex flex-column py-6 my-md-2 px-md-16"
    :style="$vuetify.breakpoint.mdAndUp ? 'max-width: 900px' : ''"
    fluid
  >
    <v-dialog v-model="dialog" width="500">
      <template v-slot:activator="{ on, attrs }">
        <div
          class="d-flex flex-column"
          style="position: fixed"
          :style="$vuetify.breakpoint.mdAndUp ? 'width: 772px' : 'width: calc(100vw - 24px)'"
          absolute
        >
          <div class="py-2 d-flex justify-start flex-column">
            <v-subheader
              class="pa-md-0 d-flex"
              :class="
                $vuetify.breakpoint.mdAndUp
                  ? 'hypothesisSubTitle'
                  : 'spHypothesisSubTitle'
              "
            >
              <p class="ma-0 font-weight-bold" color="grey darken-1">ゴール</p>
            </v-subheader>
            <v-textarea
              label="ゴールを入力"
              class="pa-0 text-h5"
              rows="1"
              auto-grow
              single-line
              solo
              flat
              hide-details
            ></v-textarea>
          </div>
          <div class="py-2 d-flex justify-start" :style="$vuetify.breakpoint.mdAndUp ? 'height: 80px' : 'height: 64px'">
            <v-subheader
              class="my-2 my-md-3 pa-md-0"
              :class="
                $vuetify.breakpoint.mdAndUp
                  ? 'hypothesisSubTitle'
                  : 'spHypothesisSubTitle'
              "
            >
              <p class="ma-0 font-weight-bold" color="grey darken-1">結果</p>
            </v-subheader>
            <v-col class="py-0 py-md-3 px-4 px-md-6">
              <v-icon class="px-1 my-2" :size="$vuetify.breakpoint.mdAndUp ? '32' : '28'"
                >mdi-check-circle-outline</v-icon
              >
              <v-icon class="px-1 my-2" :size="$vuetify.breakpoint.mdAndUp ? '32' : '28'"
                >mdi-close-circle-outline</v-icon
              >
            </v-col>
          </div>
          <div class="py-2">
            <div class="d-flex justify-space-between">
              <v-subheader
                class="pa-md-0"
                :class="
                  $vuetify.breakpoint.mdAndUp
                    ? 'hypothesisSubTitle'
                    : 'spHypothesisSubTitle'
                "
              >
                <p class="ma-0 font-weight-bold" color="grey darken-1">仮説</p>
              </v-subheader>
              <v-icon
                class="hidden-sm-and-down my-3"
                size="24"
                height="24"
                v-bind="attrs"
                v-on="on"
                >mdi-plus-circle</v-icon
              >
            </div>
            <div
              class="overflow-y-auto d-flex flex-column"
              :class="$vuetify.breakpoint.mdAndUp ? 'cardStyle' : 'spCardStyle'"
            >
              <HypothesisCards :cards="cards" />
              <!-- PC版追加カード -->
              <NewAdditionalCard :on="on" :attrs="attrs" />
            </div>
          </div>
        </div>
        <!-- PC版 -->

        <!-- スマホ版追加ボタン -->
        <SpNewAdditionalBtn :on="on" :attrs="attrs" />
      </template>
      
    </v-dialog>
  </v-container>
</template>

<script>
import HypothesisCards from "../components/Cards/HypothesisCard.vue";
import NewAdditionalCard from "../components/Cards/NewAddtionalCard.vue";
import SpNewAdditionalBtn from "../components/Buttons/SpNewAdditionalBtn.vue";

export default {
  components: {
    HypothesisCards,
    NewAdditionalCard,
    SpNewAdditionalBtn,
  },
  data: () => ({
    on: true,
    attrs: true,
    dialog: false,
    cards: ["朝ごはんを食べる", "日本海制圧"],
  }),
  methods: {
    isDisplay: function () {
      this.dialog = !this.dialog;
    },
  },
};
</script>

<style scoped lang='sass'>
.hypothesisSubTitle
    font-size: 1rem

.spHypothesisSubTitle
    font-size: 14px
    height: 24px
    padding: 0 0 0 12px

.spCardStyle
    height: calc(100vh - 224px)
    position: relative
</style>