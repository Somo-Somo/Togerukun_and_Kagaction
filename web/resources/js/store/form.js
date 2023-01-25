import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';

const state = {
    name : null,
    editObject : null,
    inputForm: false,
    submitType: null,
};

const getters = {
    name: state => state.name ? state.name : '',
    editObject: state => state.editObject ? state.editObject : null,
    inputForm: state => state.inputForm,
    submitType: state => state.submitType ? state.submitType : null,
};

const mutations = {
    setName (state, name) {
        state.name = name
    },
    setEditingObject (state, data){
        state.editObject = data
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
    setName (context, name) {
        context.commit('setName', name);
    },
    onClickCreate (context) {
        const submitType = 'create'
        context.commit('setSubmitType',submitType);
        context.commit('openForm');
    },
    async onClickEdit (context, data) {
        const submitType = 'edit'
        await context.commit('setName', data.name);
        context.commit('setEditingObject', data);
        context.commit('setSubmitType',submitType);
        context.commit('openForm');
    },
    async closeForm (context) {
        await context.commit('closeForm');
        context.commit('setName', null);
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