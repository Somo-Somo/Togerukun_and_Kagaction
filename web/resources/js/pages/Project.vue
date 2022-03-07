<template>
  <v-container
    class="d-flex flex-column py-6 my-md-2 px-md-16"
    style="max-width: 900px"
    fluid
  >
  <Header :headerTitle="category"/>
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
          <ProjectCards @onClickEdit="onClickEdit" />
          <!-- PC版追加カード -->
          <NewAdditionalCard @clickAditional="onClickCreate" :category="category" />
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
    projectList: null,
  }),
  computed: {
    ...mapState({
      apiStatus: (state) => state.auth.apiStatus,
    }),
    // 後でmapGettersからprops,$emitに移行したい
    ...mapGetters({
      name: 'form/name',
      editObject: 'form/editObject',
      inputForm: 'form/inputForm',
      submitType: 'form/submitType',
      project: 'project/project',
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
    submitForm(){
      this.$store.dispatch("form/closeForm");
      if (this.submitType === 'create') {
        this.$store.dispatch("project/createProject", {'name' : this.name});
        this.apiStatus ? this.$router.push( "project/" + this.project.uuid) : console.info('ログインしてください')
      } else if (this.submitType === 'edit') {
        // 名前を更新
        this.editObject.name = this.name;
        this.$store.dispatch("project/editProject", this.editObject);
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