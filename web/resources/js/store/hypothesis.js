import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';

const state = {
    hypothesis: {
        name: null,
        uuid: null,
    },
    hypothesisList: null,
    parent: {
        name: null,
        uuid: null,
    },
    child: null,
};

const getters = {
    hypothesisName: state => state.hypothesis.name ? state.hypothesis.name : null,
    hypothesisList: state => state.hypothesisList ? state.hypothesisList : null,
    hypothesisChildList: state => state.child ? state.child : null,
};

const mutations = {
    setInputName (state, value){
        state.hypothesis.name = value;
    },

    setParent (state, data) {
        state.parent.name = data.name;
        state.parent.uuid = data.uuid;
    },

    setChild (state, hypothesisVal) {
        state.child = state.hypothesisList.filter(hypothesis => {
            return hypothesis.parentUuid === hypothesisVal.uuid;
        });
    },

    setHypothesis (state, hypothesisVal) {
        state.hypothesis = hypothesisVal;
    },

    setHypothesisList (state, data) {
        state.hypothesisList = data;
    },

    deleteHypothesis (state, hypothesisUuid){
        delete state.hypothesisList[hypothesisUuid];
    },
}

const actions = {
    selectParent (context, data){
        context.commit('setParent', data)
    },

    setInputName (context, value) {
        context.commit('setInputName', value)
    },

    selectHypothesis (context, hypothesisVal) {
        context.commit ('setHypothesis', hypothesisVal);
        context.commit ('setChild', hypothesisVal);
    },

    async getHypothesisList (context, data) {
        await axios.get('/api/project/'+data.uuid)
        .then(response => {
            console.info('仮説一覧を追加しました');
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

    async deleteHypothesis (context, selectedDeletingHypothesis) {
        const hypothesisUuid = selectedDeletingHypothesis.uuid;
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.delete('api/hypothesis/'+ hypothesisUuid)
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