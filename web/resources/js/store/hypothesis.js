import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';

const state = {
    hypothesis: {
        name: null,
        uuid: null,
        parentUuid: null,
    }
};

const getters = {

};

const mutations = {
    setHypothesis (state, hypothesis) {
        state.hypothesis.name = hypothesis.name;
        state.hypothesis.uuid = hypothesis.uuid;
        state.hypothesis.parentUuid = hypothesis.parentUuid;
    }
}

const actions = {
    async createGoal (context, data){
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.post('api/hypothesis/goal', data);

        if (response.status == CREATED) {
            console.info("ゴールを追加しました");
            context.commit ('auth/setApiStatus', true);
            context.commit ('setHypothesis', response.data.hypothesis);
            return false;
        }

        if (response.status === UNPROCESSABLE_ENTITY) {
            console.info('エラー')
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