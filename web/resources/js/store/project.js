import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';

const state = {
    project : {
        name : null,
        uuid : null,
    },
    projectList: null
};

const getters = {
    project : state => (state.project.name && state.project.uuid) ? state.project : null,
    projectList: state => state.projectList ? state.projectList : null,
};

const mutations = {
    setProject (state, project) {
        state.project.name = project.name;
        state.project.uuid = project.uuid;
    },
    setProjectList (state, projectList){
        state.projectList = projectList;
    }
}

const actions = {
    async getProjectList (context) {
        await axios.get('/api/projects').then(response => {
            context.commit('setProjectList', response.data)
        })
        .catch(error => {
            console.info(error);
        });
    },
    async createProject (context, data) {
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.post('api/project', data);

        if (response.status == CREATED) {
            console.info("projectを追加しました");
            context.commit ('auth/setApiStatus', true);
            context.commit ('setProject', response.data.project);
            return false;
        }

        if (response.status === UNPROCESSABLE_ENTITY) {
            // context.commit ('setRegisterErrorMessages', response.data.errors);
        } else {
            context.commit ('error/setCode', response.status, {root: true});
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