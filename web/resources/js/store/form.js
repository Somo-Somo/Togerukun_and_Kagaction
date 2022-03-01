import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';

const state = {
    name : null,
};

const getters = {
    name: state => state.name ? state.name : '',
};

const mutations = {
    setName (state, name) {
        state.name = name;
    },
}

const actions = {
    setName (context, name) {
        context.commit('setName', name)
    }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};