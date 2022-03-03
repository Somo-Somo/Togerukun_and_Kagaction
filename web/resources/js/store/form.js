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
    },
    onClickCancel (state) {
        state.inputForm = false
    }
}

const actions = {
    setTitle (context, title) {
        context.commit('setTitle', title);
    },
    isDisplay (context) {
        context.commit('isDisplay');
    },
    async onClickEdit (context, data) {
        console.info(data);
        await context.commit('setTitle', data.name);
        context.commit('isDisplay');
    },
    async onClickCancel (context) {
        await context.commit('onClickCancel');
        context.commit('setTitle', null);
    },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};