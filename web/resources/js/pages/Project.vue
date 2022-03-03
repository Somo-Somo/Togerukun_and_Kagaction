<template>
  <v-container
    class="d-flex flex-column py-6 my-md-2 px-md-16"
    style="max-width: 900px"
    fluid
  >
      <template>
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
            @click="isDisplay"
            >mdi-plus-circle</v-icon
          >
        </div>
        <div
          class="overflow-y-auto d-flex flex-column"
          :class="$vuetify.breakpoint.mdAndUp ? 'cardStyle' : 'spCardStyle'"
        >
          <ProjectCards @onClickEdit="onClickEdit" />
          <!-- PC版追加カード -->
          <NewAdditionalCard @clickAditional="isDisplay" :category="category" />
        </div>
        <!-- スマホ版追加ボタン -->
        
        <SpButtomBtn @clickAditional="isDisplay" :headerTitle="category" />
      
      </template>
      <!-- 追加のフォーム -->
      <form class="form" @submit.prevent="submitForm()">
        <InputForm 
          @onClickCancel="onClickCancel" 
          @submitForm="submitForm"
          :inputForm="inputForm" 
          :category="category" 
        />
      </form>
  </v-container>
</template>

<script>
import ProjectCards from "../components/Cards/ProjectCard.vue";
import NewAdditionalCard from "../components/Cards/NewAddtionalCard.vue";
import SpButtomBtn from "../components/Buttons/SpBottomBtn.vue";
import InputForm from "../components/InputForm.vue";
import { mapGetters, mapState } from 'vuex'

export default {
  components: {
    ProjectCards,
    NewAdditionalCard,
    SpButtomBtn,
    InputForm,
  },
  data: () => ({
    category : "プロジェクト",
    projectList: null,
  }),
  computed: {
    ...mapState({
      apiStatus: (state) => state.auth.apiStatus,
    }),
    // 後でmapGettersからprops,$emitに移行したい
    ...mapGetters({
      title: 'form/title',
      inputForm: 'form/inputForm',
      project: 'project/project',
    })
  },
  methods: {
    isDisplay () {
      this.$store.dispatch("form/isDisplay");
    },
    onClickEdit(value){
      this.$store.dispatch("form/onClickEdit", value);
    },
    onClickCancel() {
      this.$store.dispatch("form/onClickCancel");
    },
    async submitForm(){
      this.$store.dispatch("form/isDisplay");
      const projectInputForm = {
        title : this.title 
      }
      await this.$store.dispatch("project/createProject", projectInputForm);

      const url = "project/" + this.project.uuid;
      
      if (this.apiStatus) {
        this.$router.push(url);
      }
    }
  },
  created() {
    this.$store.dispatch("project/getProjectList");
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