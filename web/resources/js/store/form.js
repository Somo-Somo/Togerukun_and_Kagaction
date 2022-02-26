import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';

const state = {
    name : null,
};

const getters = {
    name: state => state.name ? state.name : ''
};

const mutations = {
    setName (state, name) {
        state.name = name;
    }
}

const actions = {
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