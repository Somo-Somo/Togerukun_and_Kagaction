<template>
  <v-container
    class="d-flex flex-column py-6 my-md-2 px-md-16"
    :style="$vuetify.breakpoint.mdAndUp ? 'max-width: 900px' : ''"
    fluid
  >
    <v-dialog v-model="inputForm" width="500">
      <template v-slot:activator="{ on, attrs }">
        <div
          class="d-flex flex-column"
          style="position: fixed; "
          :style="$vuetify.breakpoint.mdAndUp ? 'width: 772px' : 'width: 90vw'"
          absolute
        >
          <div class="py-2 d-flex justify-start flex-column">
            <v-subheader class="pa-md-0 d-flex" :class="$vuetify.breakpoint.mdAndUp ? 'hypothesisSubTitle' : 'spHypothesisSubTitle'"> 
              <p class="ma-0 font-weight-bold" color="grey darken-1">ゴール</p>
            </v-subheader>
              <v-textarea
                label="ゴールを入力"
                class="pa-0 text-h5"
                padding="0"
                style="padding: 0 !important;"
                rows="1"
                auto-grow
                single-line
                solo
                flat
                hide-details
              ></v-textarea>
          </div>
          <div class="py-2 d-flex justify-start" style="height: 56px">
            <v-subheader class="my-2 pa-md-0 d-flex align-center" :class="$vuetify.breakpoint.mdAndUp ? 'hypothesisSubTitle' : 'spHypothesisSubTitle'">
              <p class="ma-0 font-weight-bold" color="grey darken-1">現状</p>
            </v-subheader>
            <v-col class="py-0 py-md-3 px-4 px-md-6">
              <v-icon class="px-1 my-1" size="32">mdi-check-circle-outline</v-icon>
              <v-icon class="px-1 my-1" size="32">mdi-close-circle-outline</v-icon>
            </v-col>
          </div>
          <div>
            <div class="d-flex justify-space-between">
              <v-subheader class="pa-md-0 d-flex" style="font-size: 1rem">
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
      <ProjectNameInput @clickCancel="isDisplay" @clickNext="isDisplay" />
    </v-dialog>
  </v-container>
</template>

<script>
import HypothesisCards from "../components/Cards/HypothesisCard.vue";
import NewAdditionalCard from "../components/Cards/NewAddtionalCard.vue";
import SpNewAdditionalBtn from "../components/Buttons/SpNewAdditionalBtn.vue";
import ProjectNameInput from "../components/Forms/ProjectNameInput.vue";

export default {
  components: {
    HypothesisCards,
    NewAdditionalCard,
    SpNewAdditionalBtn,
    ProjectNameInput,
  },
  data: () => ({
    on: true,
    attrs: true,
    inputForm: false,
    cards: ["朝ごはんを食べる", "日本海制圧"],
  }),
  methods: {
    isDisplay: function () {
      this.inputForm = !this.inputForm;
    },
  },
};
</script>

<style scoped lang='sass'>
.hypothesisSubTitle
    font-size: 18px

.spHypothesisSubTitle
    font-size: 14px
    height: 24px
    padding: 0 0 0 12px

.spCardStyle
    height: calc(100vh - 224px)
    position: relative
</style>