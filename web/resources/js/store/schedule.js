const state = {
    scheduleList: [],
};

const getters = {
    scheduleList: state => state.scheduleList ? state.scheduleList : null,
};

const mutations = {
    setScheduleList (state, data) {
        state.scheduleList = data;
    }
}

const actions = { }

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};