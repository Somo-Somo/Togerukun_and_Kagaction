import {OK, NOT_FOUND, UNPROCESSABLE_ENTITY} from '../util';
import router from "../router";

const state = {
    loading: true,
};

const getters = {
    loading: state => state.loading
};

const mutations = {
    finishedLoading (state) {
        state.loading = false
    }
}

const actions = {
    async getUserHasProjectAndHypothesis (context, route) {
        const response = await axios.get('/api/initialize')
                .catch(err => {
                    console.error(err);
                });

        if (response.status === OK) {
            context.commit ('project/setProjectList', response.data.project, { root: true });
            await context.commit ('hypothesis/setAllHypothesisList', response.data.hypothesis, { root: true });
            context.commit ('schedule/setScheduleList', response.data.schedule, { root: true });
            context.commit ('finishedLoading');
            if (route.name === "hypothesisList") {
                const projectUuid = route.params.id
                // URLのIDが存在しない場合404エラー
                if (!response.data.project[projectUuid]) context.commit ('error/setCode', NOT_FOUND, {root: true});
                context.commit ('project/setProject', response.data.project[projectUuid] , { root: true });
                context.commit ('hypothesis/selectHypothesisList', projectUuid , { root: true });
            } else if (route.name === "hypothesisDetail") {
                router.push({ path: '/schedule' });
            }            
            return false;
        }
        context.commit('setApiStatus', false);
        context.commit('error/setCode', response.status, { root: true })
    },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};