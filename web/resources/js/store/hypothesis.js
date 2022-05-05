import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';
import { v4 as uuidv4 } from 'uuid';

const state = {
    hypothesis: null,
    parentHypothesis: null,
    hypothesisList: [],
    allHypothesisList: null,
};

const getters = {
    hypothesis: state => state.hypothesis.uuid ? state.hypothesis: null,
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
        const hypothesisList = state.hypothesisList
        const newHypothesisList = []
        let hypothesisParemtOrBrother = false;
        
        for (const [key, hypothesis] of Object.entries(hypothesisList)) {
            // 追加する親仮説の場合
            if (hypothesis.uuid === newHypothesis.parentUuid ){
                hypothesis['toggle'] = "mdi-menu-right";
                hypothesis.child = true;
                hypothesisParemtOrBrother = true;
                newHypothesisList.push(hypothesis);
            } 
            // 追加する仮説と同じ階層にある仮説の場合
            else if (hypothesis.parentUuid === newHypothesis.parentUuid) {
                hypothesisParemtOrBrother = true;
                newHypothesisList.push(hypothesis);
            } 
            // 追加する仮説と同じ階層の仮説があるかつ親仮説以上の階層に仮説が戻った場合
            else if (hypothesisParemtOrBrother && newHypothesis.depth > hypothesis.depth) {
                hypothesisParemtOrBrother = false;
                newHypothesisList.push(newHypothesis);
                newHypothesisList.push(hypothesis);
            } else {
                newHypothesisList.push(hypothesis);
            }
        }

        if(hypothesisParemtOrBrother) newHypothesisList.push(newHypothesis);

        state.hypothesisList = newHypothesisList;
    },

    setHypothesisListAfterHypothesisCreation(state, hypothesisList) {
        state.hypothesisList = Object.values(hypothesisList)[0];
    },

    addGoal (state, data) {
        state.hypothesisList.push(data);
    },

    addComment (state, {hypothesis, comment}){
        const hypothesisList = state.hypothesisList;
        let hypothesisKey;
        for (const [key, todo] of Object.entries(hypothesisList)) {
            if (todo.uuid === hypothesis.uuid) hypothesisKey = key;
        }
        hypothesisList[hypothesisKey]['comments'].push(comment);
        state.hypothesisList = hypothesisList;
    },

    updateAllHypothesisList (state) {
        const projectUuid =  state.hypothesisList[0].parentUuid;
        state.allHypothesisList[projectUuid] = state.hypothesisList;
    },

    updateHypothesisAccomplish (state, accomplish){
        state.hypothesis.accomplish = accomplish;
    },

    updateDate (state, date){
        state.hypothesis.date = date;
    },

    deleteHypothesis (state, hypothesis){
        const hypothesisList = state.hypothesisList
        const newHypothesisList = [];
        let deleteHypothesisChild = false;
        let parentKey = null;
        const childList = [];
        for (const [key, value] of Object.entries(hypothesisList)) {
            // 削除する仮説の子以下の場合
            deleteHypothesisChild = deleteHypothesisChild && hypothesis.depth < value.depth ? true : false;

            if (value.uuid !== hypothesis.uuid && !deleteHypothesisChild) {
                if(value.uuid === hypothesis.parentUuid) parentKey = key;
                if (value.parentUuid === hypothesis.parentUuid) {
                    childList.push(value);
                }
                newHypothesisList.push(value);
            } else {
                deleteHypothesisChild = true;
            }
        }

        // 仮説を削除した結果、親仮説の子がいなくなった場合
        if(newHypothesisList.length && !childList.length) newHypothesisList[parentKey]['child'] = false;
        state.hypothesisList = newHypothesisList;
        state.allHypothesisList[hypothesisList[0]['parentUuid']] = newHypothesisList;
    },

    deleteComment(state, {hypothesis, comment}){
        const comments = [];
        for (const [key, value] of Object.entries(hypothesis.comments)) {
            if (value.uuid !== comment.uuid) comments.push(value);
        }
        state.hypothesis.comments = comments;
    }
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
            child: false,
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
            child: false,
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
        if (response.status !== OK) {
            context.commit ('error/setCode', response.status, {root: true});
            return false;
        }
    },

    async deleteHypothesis (context, selectedDeletingHypothesis) {
        const hypothesisUuid = selectedDeletingHypothesis.uuid;
        context.commit('deleteHypothesis', selectedDeletingHypothesis);
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.delete('/api/hypothesis/'+ hypothesisUuid)

        if (response.status !== OK) {
            context.commit ('error/setCode', response.status, {root: true});
            return;
        }
        return;
    },

    async updateAccomplish (context, {accomplish, hypothesisUuid}) {
        context.commit('updateHypothesisAccomplish', accomplish);
        if (accomplish) {
            const response = await axios.put('/api/hypothesis/'+hypothesisUuid+'/accomplish')
            if (response.status !== OK) {
                context.commit ('error/setCode', response.status, {root: true});
                return false;
            }
        } else {
            const response = await axios.delete('/api/hypothesis/'+hypothesisUuid+'/accomplish')
            if (response.status !== OK) {
                context.commit ('error/setCode', response.status, {root: true});
                return false;
            }
        }
        return; 
    },

    async updateDate (context, {date, hypothesis, project}) {
        context.commit('updateDate', date);
        context.commit ('schedule/updateScheduleList', {date:date, hypothesis:hypothesis, project:project}, {root: true});
        if (date) {
            const response = await axios.put('/api/hypothesis/'+hypothesis.uuid+'/date', {date: date})
            if (response.status !== OK) {
                context.commit ('error/setCode', response.status, {root: true});
                return false;
            }
        } else {
            const response = await axios.delete('/api/hypothesis/'+hypothesis.uuid+'/date')
            if (response.status !== OK) {
                context.commit ('error/setCode', response.status, {root: true});
                return false;
            }
        }
        return; 
    },

    async createComment (context, {hypothesis, text, user}){
        var date = new Date();
        date.setTime(date.getTime() + (9*60*60*1000));
        const str_date = date.toISOString().replace('T', ' ').substr(0, 19);
        const comment = {
            user_name: user.name,
            user_uuid: user.uuid,
            text : text,
            uuid: uuidv4(),
            created_at: str_date,
        };

        context.commit('addComment', {hypothesis:hypothesis, comment: comment});
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.post('/api/hypothesis/'+ hypothesis.uuid +'/comment', comment);

        if (response.status !== CREATED) {
            context.commit ('error/setCode', response.status, {root: true});
            return;
        }
    },

    async deleteComment (context, {hypothesis, comment}){
        context.commit('deleteComment', {hypothesis:hypothesis, comment: comment});
        const response = await axios.delete('/api/comment/'+ comment.uuid);
        if (response.status !== OK) {
            context.commit ('error/setCode', response.status, {root: true});
            return false;
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