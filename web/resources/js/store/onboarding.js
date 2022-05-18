const state = {
    onboarding: null,
  };
  
  const getters = {
    onboarding: state => state.onboarding
  }
  
  const mutations = {
    setOnboarding (state, data) {
      state.onboarding = data;
    },
  };
  
  export default {
    namespaced: true,
    state,
    getters,
    mutations,
  };
  