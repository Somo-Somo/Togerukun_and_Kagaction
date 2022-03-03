import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';

const state = {
    title : null,
    inputForm: false,
    submitType: null,
};

const getters = {
    title: state => state.title ? state.title : '',
    inputForm: state => state.inputForm,
    submitType: state => state.submitType ? state.submitType : null,
};

const mutations = {
    setTitle (state, title) {
        state.title = title
    },
    openForm (state) {
        state.inputForm = true
    },
    closeForm (state) {
        state.inputForm = false
    },
    setSubmitType (state, data) {
        state.submitType = data
    }
}

const actions = {
    setTitle (context, title) {
        context.commit('setTitle', title);
    },
    onClickCreate (context) {
        const submitType = 'create'
        context.commit('setSubmitType',submitType);
        context.commit('openForm');
    },
    async onClickEdit (context, data) {
        const submitType = 'edit'
        await context.commit('setTitle', data.name);
        context.commit('setSubmitType',submitType);
        context.commit('openForm');
    },
    async closeForm (context) {
        await context.commit('closeForm');
        context.commit('setTitle', null);
        context.commit('setSubmitType', null);
    },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};