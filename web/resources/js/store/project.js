import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';

const state = {
    name : null,
    projectList: null
};

const getters = {
    name: state => state.name ? state.name : '',
    projectList: state => state.projectList ? state.projectList : null,
};

const mutations = {
    setName (state, name) {
        state.name = name;
    },
    setProjectList (state, projectList){
        state.projectList = projectList;
        console.info(state.projectList);
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

        console.info(response)

        if (response.status == CREATED) {
            console.info('Projectが追加されました')
        }

        if (response.status === UNPROCESSABLE_ENTITY) {
            // context.commit ('setRegisterErrorMessages', response.data.errors);
        } else {
            // context.commit ('error/setCode', response.status, {root: true});
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