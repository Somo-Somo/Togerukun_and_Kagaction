import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';

const state = {
    hypothesis: null,
    hypothesisList: [],
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

    selectHypothesisList (state, projectUuid) {
        const allHypothesisList = state.allHypothesisList;
        if(allHypothesisList[projectUuid]) state.hypothesisList = allHypothesisList[projectUuid];
    },

    setAllHypothesisList (state, data) {
        state.allHypothesisList = data;
    },

    setHypothesisListAfterHypothesisCreation(state, hypothesisList) {
        state.hypothesisList = Object.values(hypothesisList)[0];
    },

    addGoal (state, data) {
        state.hypothesisList.push(data.goal);
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
        const hypothesisList = state.hypothesisList
        let newHypothesisList = [];
        for (const [key, value] of Object.entries(hypothesisList)) {
            if(value.uuid !== hypothesisUuid && value.parentUuid !== hypothesisUuid){
                newHypothesisList.push(value);
            }
        }
        state.hypothesisList = newHypothesisList;
    },
}

const actions = {
    setInputName (context, value) {
        context.commit('setInputName', value)
    },

    selectHypothesis (context, hypothesis) {
        context.commit ('setHypothesis', hypothesis);
    },

    async createGoal (context, {project, hypothesisName}){
        const goal = {
            project: project,
            name : hypothesisName
        };
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.post('/api/goal', goal);

        if (response.status === UNPROCESSABLE_ENTITY) {
            console.info('エラー')
            // context.commit ('setRegisterErrorMessages', response.data.errors);
        }

        if (response.status === CREATED) {
            response.data.goal.depth = 0;
            context.commit ('setHypothesis', response.data.goal);
            context.commit ('addGoal', response.data);
            return response.data;
        } else {
            context.commit ('error/setCode', response.status, {root: true});
            return false;
        }
    },

    async createHypothesis (context, data){
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.post('/api/hypothesis', data);

        if (response.status === UNPROCESSABLE_ENTITY) {
            console.info('エラー')
            // context.commit ('setRegisterErrorMessages', response.data.errors);
        } 

        if (response.status === CREATED) {
            context.commit ('setHypothesis', response.data.hypothesis);
            context.commit ('setHypothesisListAfterHypothesisCreation', response.data.hypothesisList);
            return;
        } else {
            context.commit ('error/setCode', response.status, {root: true});
        }
    },

    async editHypothesis (context, data) {
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.put('/api/hypothesis/'+ data.uuid, data)

        if (response.status === OK) {
            context.commit('updateHypothesisName', data);
            return false;
        } else {
            context.commit ('error/setCode', response.status, {root: true});
            return false;
        }
    },

    async deleteHypothesis (context, selectedDeletingHypothesis) {
        const hypothesisUuid = selectedDeletingHypothesis.uuid;
        context.commit('deleteHypothesis', hypothesisUuid);
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.delete('/api/hypothesis/'+ hypothesisUuid)

        if (response.status !== OK) {
            context.commit ('error/setCode', response.status, {root: true});
            return;
        }
        return;
    },

    async updateStatus (context, {click,hypothesisUuid}) {
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        if (click === 'remove') {
            const response = await axios.delete('/api/hypothesis/'+hypothesisUuid+'/status')
            if (response.status !== OK) {
                context.commit ('error/setCode', response.status, {root: true});
                return false;
            } 
        } else {
            const response = await axios.put('/api/hypothesis/'+hypothesisUuid+'/status', {status:click})
            if (response.status !== OK) {
                context.commit ('error/setCode', response.status, {root: true});
                return false;
            }
        }
        context.commit('updateHypothesisStatus', click);
        return;
    },

    async updateTodaysGoal (context, {todaysGoal, hypothesisUuid}) {
        if (todaysGoal) {
            const response = await axios.put('/api/hypothesis/'+hypothesisUuid+'/todays_goal')
            if (response.status !== OK) {
                context.commit ('error/setCode', response.status, {root: true});
                return false;
            }
        } else {
            const response = await axios.delete('/api/hypothesis/'+hypothesisUuid+'/todays_goal')
            if (response.status !== OK) {
                context.commit ('error/setCode', response.status, {root: true});
                return false;
            }
        }
        context.commit('updateHypothesisTodaysGoal', todaysGoal);
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