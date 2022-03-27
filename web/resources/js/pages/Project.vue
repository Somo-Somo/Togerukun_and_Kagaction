<template>
  <v-container
    class="d-flex flex-column py-6 my-md-2 px-md-16"
    style="max-width: 900px"
    fluid
  >
  <Header/>
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
            @click="onClickCreate"
            >mdi-plus-circle</v-icon
          >
        </div>
        <div
          class="overflow-y-auto d-flex flex-column"
          :class="$vuetify.breakpoint.mdAndUp ? 'cardStyle' : 'spCardStyle'"
        >
          <ProjectCards :projectList="projectList" @onClickEdit="onClickEdit" />
          <v-progress-circular
            class="mx-auto my-8"
            v-if="loading"
            color="grey lighten-1"
            indeterminate
          ></v-progress-circular>
          <!-- PC版追加カード -->
          <NewAdditionalCard v-if="!loading" @clickAditional="onClickCreate" :category="category" />
        </div>
        <!-- スマホ版追加ボタン -->
        <SpButtomBtn @clickAditional="onClickCreate" :headerTitle="category" />
      
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
import Header from "../components/Header.vue";
import ProjectCards from "../components/Cards/ProjectCard.vue";
import NewAdditionalCard from "../components/Cards/NewAddtionalCard.vue";
import SpButtomBtn from "../components/Buttons/SpBottomBtn.vue";
import InputForm from "../components/InputForm.vue";
import { mapGetters, mapState } from 'vuex'

export default {
  components: {
    Header,
    ProjectCards,
    NewAdditionalCard,
    SpButtomBtn,
    InputForm,
  },
  data: () => ({
    category : "プロジェクト",
  }),
  computed: {
    ...mapState({
      apiStatus: (state) => state.auth.apiStatus,
    }),
    // 後でmapGettersからprops,$emitに移行したい
    ...mapGetters({
      loading: 'initialize/loading',
      name: 'form/name',
      editObject: 'form/editObject',
      inputForm: 'form/inputForm',
      submitType: 'form/submitType',
      project: 'project/project',
      projectList: 'project/projectList',
    })
  },
  methods: {
    onClickCreate () {
      this.$store.dispatch("form/onClickCreate");
    },
    onClickEdit(value){
      this.$store.dispatch("form/onClickEdit", value);
    },
    onClickCancel() {
      this.$store.dispatch("form/closeForm");
    },
    async submitForm(){
      if (this.submitType === 'create') {
        const response = await this.$store.dispatch("project/createProject", {'name' : this.name});
        this.$store.dispatch("form/closeForm");
        this.$router.push("/project/" + response.project.uuid);
      } else if (this.submitType === 'edit') {
        // 名前を更新
        this.editObject.name = this.name;
        this.$store.dispatch("project/editProject", this.editObject);
        this.$store.dispatch("form/closeForm");
      }
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