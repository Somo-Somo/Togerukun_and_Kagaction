import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';

const state = {
    project : null,
    projectList: {},
};

const getters = {
    project : state => state.project ? state.project : null,
    projectList: state => state.projectList ? state.projectList : null,
};

const mutations = {
    setProject (state, project) {
        state.project = project;
    },
    setProjectList (state, projectList){
        state.projectList = Object.keys(projectList).length ? projectList : {};
    },
    addProjectList (state, project) {
        state.projectList[project.uuid] = project;
    },
    updateProject (state, data) {
        state.projectList[data.uuid]['name'] = data.name;
    },
    deleteProject (state, projectUuid){
        delete state.projectList[projectUuid];
    },
}

const actions = {
    selectProject (context, project){
        context.commit ('setProject', project);
        context.commit ('todo/selectTodoList', project.uuid, { root: true });
    },
    async createProject (context, data) {
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.post('/api/project', data);

        // バリデーションエラー
        if (response.status === UNPROCESSABLE_ENTITY) {
            // context.commit ('setRegisterErrorMessages', response.data.errors);
        }

        if (response.status === CREATED) {
            context.commit ('auth/setApiStatus', true, {root: true});
            context.commit ('setProject', response.data.project);
            context.commit ('addProjectList', response.data.project);
            context.commit ('todo/selectTodoList', response.data.project.uuid, {root: true});
            return response.data;
        }
        else {
            context.commit ('error/setCode', response.status, {root: true});
            return false
        }
    },
    async editProject (context, data) {
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.put('/api/project/'+ data.uuid, data)

        if (response.status === OK) {
            context.commit ('auth/setApiStatus', true, {root: true});
            context.commit('updateProject', data);
            return false;
        }
        else {
            context.commit ('error/setCode', response.status, {root: true});
            return false
        }
    },
    async deleteProject (context, selectedDeletingProject) {
        const projectUuid = selectedDeletingProject.uuid;
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.delete('/api/project/'+ projectUuid)

        if (response.status === OK) {
            context.commit ('auth/setApiStatus', true, {root: true});
            context.commit('deleteProject', projectUuid);
            return false;
        }
        else {
            context.commit ('error/setCode', response.status, {root: true});
            return false
        }
    }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};