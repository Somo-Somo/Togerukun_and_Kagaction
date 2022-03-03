import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';

const state = {
    title : null,
    inputForm: false,
};

const getters = {
    title: state => state.title ? state.title : '',
    inputForm: state => state.inputForm,
};

const mutations = {
    setTitle (state, title) {
        state.title = title;
    },
    isDisplay (state) {
        state.inputForm = true
        console.info(state.inputForm)
    },
    onClickCancel (state) {
        state.inputForm = false
        console.info(state.inputForm)
    }
}

const actions = {
    setTitle (context, title) {
        context.commit('setTitle', title)
    },
    isDisplay (context) {
        context.commit('isDisplay')
    },
    onClickCancel (context) {
        context.commit('onClickCancel')
    },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};