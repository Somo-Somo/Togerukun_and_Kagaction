import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';

const state = {
    title : null,
    inputForm: false,
};

const getters = {
    title: state => state.title ? state.title : '',
    inputForm: state => state.inputForm ? state.inputForm : '',
};

const mutations = {
    setTitle (state, title) {
        state.title = title;
    },
    isDisplay (state) {
        state.inputForm = !state.inputForm
    }
}

const actions = {
    setTitle (context, title) {
        context.commit('setTitle', title)
    },
    isDisplay (context) {
        context.commit('isDisplay')
    }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};