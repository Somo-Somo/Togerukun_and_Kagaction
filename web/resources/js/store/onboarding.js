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
      async finishedOnboarding (context){
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.post ('/api/onboarding');
        if (response.status === OK) {
            context.commit ('setOnboarding', false);
        } else {
            context.commit ('error/setCode', response, {root: true});
        }
        return;
      }
  }
  
  export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
  };
  