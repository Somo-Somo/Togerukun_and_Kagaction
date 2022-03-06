import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';
import router from "../router";

const state = {

};

const getters = {

};

const mutations = {

}

const actions = {
    getUserHasProjectAndHypothesis (context, route) {
        axios.get('/api/initialize')
            .then(response => {
                console.info('プロジェクトと仮説を取得しました');                
                console.info(response.data);                
                context.commit ('hypothesis/setAllHypothesisList', response.data, { root: true });
                if (route.name === "project") {

                } else if (route.name === "hypothesisList") {
                    context.commit ('hypothesis/setHypothesisList', route.params.id, { root: true });
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