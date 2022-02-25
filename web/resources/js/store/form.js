const state = {
    name : null,
    uuid : null,
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

}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};