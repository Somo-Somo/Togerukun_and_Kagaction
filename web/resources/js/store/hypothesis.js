import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';

const state = {
    hypothesis: null,
    hypothesisList: null,
    allHypothesisList: null,
};

const getters = {
    hypothesis: state => (state.hypothesis.name && state.hypothesis.uuid) ? state.hypothesis: null,
    hypothesisList: state => state.hypothesisList ? state.hypothesisList : null,
};

const mutations = {
    setInputName (state, value){
        state.hypothesis.name = value.name;
    },

    setHypothesis (state, hypothesis) {
        state.hypothesis = hypothesis;
    },

    setHypothesisList (state, projectUuid) {
        const allHypothesisList = state.allHypothesisList;
        state.hypothesisList = allHypothesisList[projectUuid];
    },

    setAllHypothesisList (state, data) {
        state.allHypothesisList = data;
    },

    updateHypothesisName (state, data) {
        state.hypothesisList[data.uuid]['name'] = data.name;
    },

    updateHypothesisStatus (state, click){
        if (click === 'success') {
            state.hypothesis.status = state.hypothesis.status === 'success' ? null : 'success';
        } else if (click === 'failure') {
            state.hypothesis.status = state.hypothesis.status === 'failure' ? null : 'failure';
        } else if (click === 'remove') {
            state.hypothesis.status = null;
        }
     },

    updateHypothesisTodaysGoal (state, todaysGoal){
        state.hypothesis.todaysGoal = todaysGoal;
    },

    deleteHypothesis (state, hypothesisUuid){
        delete state.hypothesisList[hypothesisUuid];
    },
}

const actions = {
    setInputName (context, value) {
        context.commit('setInputName', value)
    },

    selectHypothesis (context, hypothesis) {
        context.commit ('setHypothesis', hypothesis);
    },

    async createGoal (context, data){
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.post('/api/goal', data);
        console.info(response);

        if (response.status == CREATED) {
            console.info("ゴールを追加しました");
            context.commit ('auth/setApiStatus', true);
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
                context.commit('updateHypothesisName', data);
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
    },

    async updateStatus (context, {click,hypothesisUuid}) {
        context.commit('updateHypothesisStatus', click);
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        if (click === 'remove') {
            await axios.delete('/api/hypothesis/'+hypothesisUuid+'/status')
                .then(response => {
                    console.info(response);
                    return;
                }).catch(error => {
                    console.info(error);
                });
        } else {
            await axios.put('/api/hypothesis/'+hypothesisUuid+'/status', {status:click})
                .then(response => {
                    console.info(response);
                    return;
                }).catch(error => {
                    console.info(error);
                });
        }
    },

    async updateTodaysGoal (context, {todaysGoal, hypothesisUuid}) {
        context.commit('updateHypothesisTodaysGoal', todaysGoal);
        if (todaysGoal) {
            await axios.put('/api/hypothesis/'+hypothesisUuid+'/todays_goal')
                .then(response => {
                    console.info(response);
                    return;
                }).catch(error => {
                    console.info(error);
                });
        } else {
            await axios.delete('/api/hypothesis/'+hypothesisUuid+'/todays_goal')
                .then(response => {
                    console.info(response);
                    return;
                }).catch(error => {
                    console.info(error);
                });
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