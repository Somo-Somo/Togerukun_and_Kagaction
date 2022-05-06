const state = {
    scheduleList: [],
};

const getters = {
    scheduleList: state => state.scheduleList ? state.scheduleList : null,
};

const mutations = {
    setScheduleList (state, data) {
        state.scheduleList = data;
    },
    updateScheduleList (state, {date, todo, project}){
        const scheduleList = state.scheduleList;
        const newScheduleList = [];

        //該当のtodoは保存、削除かかわらず新しい予定リストに入れない
        for (const [key, value] of Object.entries(scheduleList)) {
            if (value.uuid !== todo.uuid) {
                newScheduleList.push(value);
            }
        }

        // 日にちがある場合は予定リストに入れる
        if (date) {
            todo.date = date;
            todo.projectUuid = project.uuid;
            newScheduleList.push(todo);
        }
        
        // 新しい予定リストを日付順にソート
        let sortNewScheduleList = newScheduleList.sort(function(a, b) {
            return (a.date < b.date) ? -1 : 1;  //オブジェクトの昇順ソート
        });
        state.scheduleList = sortNewScheduleList;
    },
}

const actions = { }

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};