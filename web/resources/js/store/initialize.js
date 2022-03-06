import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';

const state = {

};

const getters = {

};

const mutations = {

}

const actions = {
    getUserHasProjectAndHypothesis (context) {
        axios.get('/api/initialize')
            .then(response => {
                console.info('プロジェクトと仮説を取得しました');                
                console.info(response.data);                
                context.commit ('hypothesis/setAllHypothesisList', response.data, { root: true });
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