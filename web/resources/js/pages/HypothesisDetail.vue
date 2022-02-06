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
          style="position: fixed; width: 772px"
          absolute
        >
          <div>
            <v-subheader class="pa-md-0 d-flex" style="font-size: 1rem">
              <p class="ma-0 font-weight-bold" color="grey darken-1">ゴール</p>
            </v-subheader>
            <v-form>
              <v-text-field
                label="ゴールを入力"
                single-line
                solo
              ></v-text-field>
            </v-form>
          </div>
          <div class="d-flex justify-start">
            <v-subheader class="pa-md-0 d-flex" style="font-size: 1rem">
              <p class="ma-0 font-weight-bold" color="grey darken-1">現状</p>
            </v-subheader>
            <v-col>
              <v-icon>mdi-check-circle-outline</v-icon>
              <v-icon>mdi-close-circle-outline</v-icon>
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
.tabStyle
    width: 772px

.spTabStyle
    width: 96vw
    left: -4vw

.cardStyle

.spCardStyle
    height: calc(100vh - 224px)
    position: relative
</style>