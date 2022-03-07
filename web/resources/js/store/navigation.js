const state = {
    opendNavigation: true,
};

const getters = {
    openedNavigation: state => state.opendNavigation,
};

const mutations = {
    changeNavState (state) {
        state.opendNavigation = !state.opendNavigation;
    }
}

const actions = {
    changeNavState (context) {
        context.commit('changeNavState');
    }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};