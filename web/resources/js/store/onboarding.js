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
      async finishedOnboarding (context, onboardingQuestionsAndAnswers){
        const onboarding = {
            projectName: onboardingQuestionsAndAnswers[0].answer,
            goalName: onboardingQuestionsAndAnswers[1].answer,
            goalDate: onboardingQuestionsAndAnswers[2].answer
        };
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.post('/api/onboarding',onboarding);
        if (response.status === OK) {
            context.commit ('setOnboarding', false);
        } else {
            context.commit ('error/setCode', response, {root: true});
            router.push({ path: '/500' });
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
  