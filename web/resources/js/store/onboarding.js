 import {OK} from '../util';
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

  const actions = {
      finishedOnboarding (context, response){
        if (response === OK) {
            context.commit ('setOnboarding', false);
        } else {
            context.commit ('error/setCode', response, {root: true});
        }
      }
  }
  
  export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
  };
  