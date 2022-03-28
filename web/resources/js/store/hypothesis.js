import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';
import { v4 as uuidv4 } from 'uuid';

const state = {
    hypothesis: null,
    parentHypothesis: null,
    hypothesisList: [],
    allHypothesisList: null,
};

const getters = {
    hypothesis: state => (state.hypothesis.name && state.hypothesis.uuid) ? state.hypothesis: null,
    parentHypothesis: state => state.parentHypothesis ? state.parentHypothesis: null,
    hypothesisList: state => state.hypothesisList ? state.hypothesisList : null,
};

const mutations = {
    setInputName (state, value){
        state.hypothesis.name = value.name;
    },

    setHypothesis (state, hypothesis) {
        state.hypothesis = hypothesis;
    },

    setParentHypothesis (state, hypothesis) {
        state.parentHypothesis = null;
        const hypothesisList = state.hypothesisList
        for (const [key, value] of Object.entries(hypothesisList)) {
            if(value.uuid === hypothesis.parentUuid){
                state.parentHypothesis = value;
            }
        }
    },

    selectHypothesisList (state, projectUuid) {
        const allHypothesisList = state.allHypothesisList;
        state.hypothesisList = allHypothesisList[projectUuid] ? 
            allHypothesisList[projectUuid] : [];
    },

    setAllHypothesisList (state, data) {
        state.allHypothesisList = data;
    },

    addHypothesisForHypothesisList (state, newHypothesis){
        console.info(state.hypothesisList);
        const hypothesisList = state.hypothesisList
        const newHypothesisList = []
        let addHypothesisParentOrBrother = false;
        
        for (const [key, hypothesis] of Object.entries(hypothesisList)) {
            if (hypothesis.uuid === newHypothesis.parentUuid || hypothesis.parentUuid === newHypothesis.parentUuid){
                addHypothesisParentOrBrother = true;
                newHypothesisList.push(hypothesis);
            } else if (addHypothesisParentOrBrother) {
                addHypothesisParentOrBrother = false;
                newHypothesisList.push(newHypothesis);
                newHypothesisList.push(hypothesis);
            } else {
                newHypothesisList.push(hypothesis);
            }
        }
        if(addHypothesisParentOrBrother) newHypothesisList.push(newHypothesis);
        state.hypothesisList = newHypothesisList;
    },

    setHypothesisListAfterHypothesisCreation(state, hypothesisList) {
        state.hypothesisList = Object.values(hypothesisList)[0];
    },

    addGoal (state, data) {
        state.hypothesisList.push(data);
    },

    updateAllHypothesisList (state) {
        const projectUuid =  state.hypothesisList[0].parentUuid;
        state.allHypothesisList[projectUuid] = state.hypothesisList;
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
        context.commit ('setParentHypothesis', hypothesis);
    },

    async createGoal (context, {project, hypothesisName}){
        const goal = {
            name : hypothesisName,
            uuid: uuidv4(),
            parentUuid: project.uuid,
            depth: 0,
            noChild: true,
        };

        await context.commit ('addGoal', goal);
        context.commit ('updateAllHypothesisList');

        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.post('/api/goal', goal);

        if (response.status === UNPROCESSABLE_ENTITY) {
            console.info('エラー')
            // context.commit ('setRegisterErrorMessages', response.data.errors);
        }

        if (response.status !== CREATED) {
            context.commit ('error/setCode', response.status, {root: true});
            return false;
        }
    },

    async createHypothesis (context, {parent, name}){
        const hypothesis = {
            name : name,
            uuid: uuidv4(),
            parentUuid: parent.uuid,
            depth: Number(parent.depth) + 1,
            noChild: true,
        };

        await context.commit ('addHypothesisForHypothesisList', hypothesis);
        context.commit ('updateAllHypothesisList');

        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.post('/api/hypothesis', hypothesis);

        if (response.status === UNPROCESSABLE_ENTITY) {
            console.info('エラー')
            // context.commit ('setRegisterErrorMessages', response.data.errors);
        } 

        if (response.status !== CREATED) {
            context.commit ('error/setCode', response.status, {root: true});
            return;
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