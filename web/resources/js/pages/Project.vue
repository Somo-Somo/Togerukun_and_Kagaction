<template>
  <v-container
    class="d-flex flex-column py-6 my-md-2 px-md-16"
    style="max-width: 900px"
    fluid
  >
    <v-dialog v-model="dialog" width="500">
      <template v-slot:activator="{ on, attrs }">
        <div
          class="d-flex justify-space-between"
          style="position: fixed; width: 772px"
          absolute
        >
          <v-subheader class="pa-md-0 d-flex" style="font-size: 1rem">
            <p class="ma-0 font-weight-bold" color="grey darken-1">
              プロジェクト一覧
            </p>
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
          <ProjectCards />
          <!-- PC版追加カード -->
          <NewAdditionalCard :on="on" :attrs="attrs" :category="projects.category" />
        </div>
        <!-- スマホ版追加ボタン -->
        
        <SpButtomBtn :on="on" :attrs="attrs" :headerTitle="projects.category" />
      
      </template>
      <!-- 追加のフォーム -->
      <form class="form" @submit.prevent="submitForm()">
        <InputForm @clickCancel="isDisplay" @submitForm="submitForm" :dialog="dialog" :addingCard="projects" />
      </form>
    </v-dialog>
  </v-container>
</template>

<script>
import ProjectCards from "../components/Cards/ProjectCard.vue";
import NewAdditionalCard from "../components/Cards/NewAddtionalCard.vue";
import SpButtomBtn from "../components/Buttons/SpBottomBtn.vue";
import InputForm from "../components/InputForm.vue";
import { mapGetters } from 'vuex'

export default {
  components: {
    ProjectCards,
    NewAdditionalCard,
    SpButtomBtn,
    InputForm,
  },
  data: () => ({
    on: true,
    attrs: true,
    dialog: false,
    projects: {category: "プロジェクト"},
  }),
  computed: {
    ...mapGetters({
      name: 'form/name'
    })
  },
  methods: {
    isDisplay: function () {
      this.dialog = !this.dialog;
    },
    async submitForm(){
      this.dialog = !this.dialog
      const project = {
        name : this.name 
      }
      console.info(project)
      await this.$store.dispatch("form/createProject", project);
    }
  },
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