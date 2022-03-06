import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';

const state = {
    hypothesis: {
        name: null,
        uuid: null,
    },
    hypothesisList: null,
    allHypothesisList: null,
    parent: {
        name: null,
        uuid: null,
    },
};

const getters = {
    hypothesis: state => (state.hypothesis.name && state.hypothesis.uuid) ? state.hypothesis: null,
    hypothesisList: state => state.hypothesisList ? state.hypothesisList : null,
};

const mutations = {
    setInputName (state, value){
        state.hypothesis.name = value.name;
    },

    setParent (state, data) {
        state.parent.name = data.name;
        state.parent.uuid = data.uuid;
    },

    setHypothesis (state, hypothesisVal) {
        state.hypothesis = hypothesisVal;
    },

    setHypothesisList (state, data) {
        const allHypothesisList = state.allHypothesisList;
        state.hypothesisList = allHypothesisList[data.uuid];
        console.info(state.hypothesisList);
    },

    setAllHypothesisList (state, data) {
        state.allHypothesisList = data;
    },

    updateHypothesis (state, data) {
        console.info(data)
        state.hypothesisList[data.uuid]['name'] = data.name;
    },

    deleteHypothesis (state, hypothesisUuid){
        delete state.hypothesisList[hypothesisUuid];
    },
}

const actions = {
    setInputName (context, value) {
        context.commit('setInputName', value)
    },

    selectHypothesis (context, hypothesisVal) {
        context.commit ('setHypothesis', hypothesisVal);
    },

    async getHypothesisList (context, data) {
        await axios.get('/api/project/'+data.uuid)
        .then(response => {
            console.info('仮説一覧を取得しました');
            console.info(response);
            context.commit ('setHypothesisList', response.data);
            return false;
        })
        .catch(error => {
            console.info(error);
        });
    },

    async createGoal (context, data){
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.post('/api/goal', data);
        console.info(response);

        if (response.status == CREATED) {
            console.info("ゴールを追加しました");
            context.commit ('auth/setApiStatus', true);
            context.commit ('setParent', response.data.parent);
            context.commit ('setHypothesis', response.data.hypothesis);
            return response.data;
        }

        if (response.status === UNPROCESSABLE_ENTITY) {
            console.info('エラー')
            // context.commit ('setRegisterErrorMessages', response.data.errors);
        } else {
            context.commit ('error/setCode', response.status, {root: true});
        }
    },

    async createHypothesis (context, data){
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.post('/api/hypothesis', data);
        console.info(response);

        if (response.status == CREATED) {
            console.info("ゴールを追加しました");
            context.commit ('auth/setApiStatus', true);
            context.commit ('setParent', response.data.parent);
            context.commit ('setHypothesis', response.data.hypothesis);
            return response.data;
        }

        if (response.status === UNPROCESSABLE_ENTITY) {
            console.info('エラー')
            // context.commit ('setRegisterErrorMessages', response.data.errors);
        } else {
            context.commit ('error/setCode', response.status, {root: true});
        }
    },

    async editHypothesis (context, data) {
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.put('/api/hypothesis/'+ data.uuid, data)
            .then(response => {
                console.info('仮説を更新しました');
                context.commit ('auth/setApiStatus', true);
                context.commit('updateHypothesis', data);
                return;
            }).catch(error => {
                console.info(error);
            });
    },

    async deleteHypothesis (context, selectedDeletingHypothesis) {
        const hypothesisUuid = selectedDeletingHypothesis.uuid;
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.delete('/api/hypothesis/'+ hypothesisUuid)
            .then(response => {
                console.info('仮説を削除しました');
                context.commit ('auth/setApiStatus', true);
                context.commit('deleteHypothesis', hypothesisUuid);
                return;
            }).catch(error => {
                console.info(error);
            });

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