import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';
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
    getUserHasProjectAndHypothesis (context, route) {
        axios.get('/api/initialize')
            .then(response => {
                console.info('プロジェクトと仮説を取得しました');                
                console.info(response.data);                
                context.commit ('project/setProjectList', response.data.project, { root: true });
                context.commit ('hypothesis/setAllHypothesisList', response.data.hypothesis, { root: true });
                context.commit ('finishedLoading');
                if (route.name === "hypothesisList") {
                    const projectUuid = route.params.id
                    context.commit ('project/setProject', response.data.project[projectUuid] , { root: true });
                    context.commit ('hypothesis/setHypothesisList', projectUuid , { root: true });
                } else if (route.name === "hypothesisDetail") {
                    router.push({ path: '/projects' });
                }            
                return false;
            })
            .catch(error => {
                console.info(error);
            });
    },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};