import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';
import { v4 as uuidv4 } from 'uuid';

const state = {
    todo: null,
    parentTodo: null,
    todoList: [],
    allTodoList: null,
};

const getters = {
    todo: state => state.todo.uuid ? state.todo: null,
    parentTodo: state => state.parentTodo ? state.parentTodo: null,
    todoList: state => state.todoList ? state.todoList : null,
};

const mutations = {
    setInputName (state, value){
        state.todo.name = value.name;
    },

    setTodo (state, todo) {
        state.todo = todo;
    },

    setParentTodo (state, todo) {
        state.parentTodo = null;
        const todoList = state.todoList
        for (const [key, value] of Object.entries(todoList)) {
            if(value.uuid === todo.parentUuid){
                state.parentTodo = value;
            }
        }
    },

    selectTodoList (state, projectUuid) {
        const allTodoList = state.allTodoList;
        state.todoList = allTodoList[projectUuid] ? allTodoList[projectUuid] : [];
    },

    setAllTodoList (state, data) {
        state.allTodoList = data;
    },

    addTodoForTodoList (state, newTodo){
        const todoList = state.todoList
        const newTodoList = []
        let todoParentOrBrother = false;
        let newTodoSpaces = []
        
        for (const [key, todo] of Object.entries(todoList)) {
            // 追加する親仮説の場合
            if (todo.uuid === newTodo.parentUuid ){
                todo.child = true;
                todoParentOrBrother = true;
                newTodoSpaces = todo.leftSideOfLine;
                newTodoSpaces.push({'lastChild': true});
                newTodoList.push(todo);
            } 
            // 追加する仮説と同じ階層にある仮説の場合
            else if (todo.parentUuid === newTodo.parentUuid) {
                todoParentOrBrother = true;
                if (todo.leftSideOfLine[newTodo.depth]) todo.leftSideOfLine[newTodo.depth] = {'lastChild': false};
                newTodoList.push(todo);
            } 
            // 追加する仮説と同じ階層あるいは子の階層だった時
            else if (todoParentOrBrother && newTodo.depth <= todo.depth) {
                if (todo.leftSideOfLine[newTodo.depth]) todo.leftSideOfLine[newTodo.depth] = {'lastChild': false};
                newTodoList.push(todo);
            }
            // 追加する仮説と同じ階層の仮説があるかつ親仮説以上の階層に仮説が戻った場合
            else if (todoParentOrBrother && newTodo.depth > todo.depth) {
                todoParentOrBrother = false;
                newTodo['leftSideOfLine'] = newTodoSpaces;
                newTodoList.push(newTodo);
                newTodoList.push(todo);
            } else {
                newTodoList.push(todo);
            }
        }

        if(todoParentOrBrother) newTodoList.push(newTodo);

        state.todoList = newTodoList;
    },

    setTodoListAfterTodoCreation(state, todoList) {
        state.todoList = Object.values(todoList)[0];
    },

    addGoal (state, data) {
        state.todoList.push(data);
    },

    addComment (state, {todo, comment}){
        const todoList = state.todoList;
        let todoKey;
        for (const [key, value] of Object.entries(todoList)) {
            if (value.uuid === todo.uuid) todoKey = key;
        }
        todoList[todoKey]['comments'].push(comment);
        state.todoList = todoList;
    },

    updateAllTodoList (state) {
        const projectUuid =  state.todoList[0].parentUuid;
        state.allTodoList[projectUuid] = state.todoList;
    },

    updateTodoAccomplish (state, accomplish){
        state.todo.accomplish = accomplish;
    },

    updateDate (state, date){
        state.todo.date = date;
    },

    deleteTodo (state, todo){
        const todoList = state.todoList
        const newTodoList = [];
        let deleteTodoChild = false;
        let parentKey = null;
        let lastChildKey = null;
        let deleteTodoKey = null;
        for (const [key, value] of Object.entries(todoList)) {
            // 削除する仮説の子以下の場合その子の仮説も削除していく
            deleteTodoChild = deleteTodoChild && todo.depth < value.depth ? true : false;
            // 削除しないtodoの場合
            if (value.uuid !== todo.uuid && !deleteTodoChild) {
                // 削除する親のtodoの時
                if(value.uuid === todo.parentUuid){
                    console.info(key);
                    parentKey = key;
                }
                // 同じ階層のtodoの時
                if (value.parentUuid === todo.parentUuid) {
                    lastChildKey = key;
                }
                newTodoList.push(value);
            } else {
                if (value.uuid === todo.uuid) deleteTodoKey = key;
                deleteTodoChild = true;
            }
        }

        // 削除するTodoと同じ階層の最後のTodoからその子どもたち全てlastChild=trueに
        if (lastChildKey) {
            for (let key = lastChildKey; key < deleteTodoKey; key++) {
                newTodoList[key]['leftSideOfLine'].splice(todo.depth, 1 , {'lastChild': true});
            }
        }

        // 仮説を削除した結果、親仮説の子がいなくなった場合
        if(newTodoList.length && !lastChildKey) newTodoList[parentKey]['child'] = false;
        state.todoList = newTodoList;
        state.allTodoList[todoList[0]['parentUuid']] = newTodoList;
    },

    deleteComment(state, {todo, comment}){
        const comments = [];
        for (const [key, value] of Object.entries(todo.comments)) {
            if (value.uuid !== comment.uuid) comments.push(value);
        }
        state.todo.comments = comments;
    }
}

const actions = {
    setInputName (context, value) {
        context.commit('setInputName', value)
    },

    selectTodo (context, todo) {
        context.commit ('setTodo', todo);
        context.commit ('setParentTodo', todo);
    },

    async createGoal (context, {project, todoName}){
        const goal = {
            name : todoName,
            uuid: uuidv4(),
            parentUuid: project.uuid,
            depth: 0,
            comments: [],
            child: false,
        };

        await context.commit ('addGoal', goal);
        context.commit ('updateAllTodoList');

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

    async createTodo (context, {parent, name}){
        const todo = {
            name : name,
            uuid: uuidv4(),
            parentUuid: parent.uuid,
            depth: Number(parent.depth) + 1,
            comments: [],
            child: false,
        };

        await context.commit ('addTodoForTodoList', todo);
        context.commit ('updateAllTodoList');

        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.post('/api/todo', todo);

        if (response.status === UNPROCESSABLE_ENTITY) {
            console.info('エラー')
            // context.commit ('setRegisterErrorMessages', response.data.errors);
        } 

        if (response.status !== CREATED) {
            context.commit ('error/setCode', response.status, {root: true});
            return;
        }
    },

    async editTodo (context, data) {
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.put('/api/todo/'+ data.uuid, data)
        if (response.status !== OK) {
            context.commit ('error/setCode', response.status, {root: true});
            return false;
        }
    },

    async deleteTodo (context, selectedDeletingTodo) {
        const todoUuid = selectedDeletingTodo.uuid;
        context.commit('deleteTodo', selectedDeletingTodo);
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.delete('/api/todo/'+ todoUuid)

        if (response.status !== OK) {
            context.commit ('error/setCode', response.status, {root: true});
            return;
        }
        return;
    },

    async updateAccomplish (context, todo) {
        context.commit('updateTodoAccomplish', todo.accomplish);
        if (todo.accomplish) {
            const response = await axios.put('/api/todo/'+todo.uuid+'/accomplish')
            if (response.status !== OK) {
                context.commit ('error/setCode', response.status, {root: true});
                return false;
            }
        } else {
            const response = await axios.delete('/api/todo/'+todo.uuid+'/accomplish')
            if (response.status !== OK) {
                context.commit ('error/setCode', response.status, {root: true});
                return false;
            }
        }
        return; 
    },

    async updateDate (context, {date, todo, project}) {
        context.commit('updateDate', date);
        context.commit ('schedule/updateScheduleList', {date:date, todo:todo, project:project}, {root: true});
        if (date) {
            const response = await axios.put('/api/todo/'+todo.uuid+'/date', {date: date})
            if (response.status !== OK) {
                context.commit ('error/setCode', response.status, {root: true});
                return false;
            }
        } else {
            const response = await axios.delete('/api/todo/'+todo.uuid+'/date')
            if (response.status !== OK) {
                context.commit ('error/setCode', response.status, {root: true});
                return false;
            }
        }
        return; 
    },

    async createComment (context, {todo, text, user}){
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

        context.commit('addComment', {todo:todo, comment: comment});
        await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
        const response = await axios.post('/api/todo/'+ todo.uuid +'/comment', comment);

        if (response.status !== CREATED) {
            context.commit ('error/setCode', response.status, {root: true});
            return;
        }
    },

    async deleteComment (context, {todo, comment}){
        context.commit('deleteComment', {todo:todo, comment: comment});
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