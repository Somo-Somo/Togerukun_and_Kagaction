import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';

const state = {
    name : null,
    uuid : null,
    projectList: null
};

const getters = {
    name: state => state.name ? state.name : '',
    uuid: state => state.uuid ? state.uuid : '',
    projectList: state => state.projectList ? state.projectList : null,
};

const mutations = {
    setName (state, name) {
        state.name = name;
    },
    setUuid (state, uuid) {
        state.uuid = uuid;
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
        console.info(response.data.project);
        console.info(response.status);

        if (response.status == CREATED) {
            console.info("projectを追加しました");
            context.commit ('auth/setApiStatus', true);
            context.commit ('setName', response.data.project.name);
            context.commit ('setUuid', response.data.project.uuid);
            return false;
        }

        if (response.status === UNPROCESSABLE_ENTITY) {
            console.info(response.data.errors)
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