import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';

const state = {
    parent: {
        name: null,
        uuid: null,
    },
    hypothesis: {
        name: null,
        uuid: null,
    }
};

const getters = {
    hypothesisName: state => state.hypothesis.name ? state.hypothesis.name : null,
};

const mutations = {
    setInputName (state, value){
        state.hypothesis.name = value;
    },

    setParent (state, data) {
        state.parent.name = data.name;
        state.parent.uuid = data.uuid;
    },

    setHypothesis (state, hypothesis) {
        state.hypothesis.name = hypothesis.name;
        state.hypothesis.uuid = hypothesis.uuid;
    }
}

const actions = {
    selectParent (context, data){
        context.commit('setParent', data)
    },

    setInputName (context, value) {
        context.commit('setInputName', value)
    },

    async createGoal (context, data){
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.post('/api/goal', data);
        console.info(response);

        if (response.status == CREATED) {
            console.info("ゴールを追加しました");
            context.commit ('auth/setApiStatus', true);
            context.commit ('setParent', response.data.parent);
            context.commit ('setHypothesis', response.data.hypothesis);
            return response.data;
        }

        if (response.status === UNPROCESSABLE_ENTITY) {
            console.info('エラー')
            // context.commit ('setRegisterErrorMessages', response.data.errors);
        } else {
            context.commit ('error/setCode', response.status, {root: true});
        }
    },

    async createHypothesis (context, data){
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.post('/api/hypothesis', data);
        console.info(response);

        if (response.status == CREATED) {
            console.info("ゴールを追加しました");
            context.commit ('auth/setApiStatus', true);
            context.commit ('setParent', response.data.parent);
            context.commit ('setHypothesis', response.data.hypothesis);
            return response.data;
        }

        if (response.status === UNPROCESSABLE_ENTITY) {
            console.info('エラー')
            // context.commit ('setRegisterErrorMessages', response.data.errors);
        } else {
            context.commit ('error/setCode', response.status, {root: true});
        }
    },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};