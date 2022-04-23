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
    updateScheduleList (state, {date, hypothesis, project}){
        console.info(date);
        console.info(hypothesis);
        const scheduleList = state.scheduleList;
        const newScheduleList = [];

        //該当のtodoは保存、削除かかわらず新しい予定リストに入れない
        for (const [key, todo] of Object.entries(scheduleList)) {
            if (todo.uuid !== hypothesis.uuid) {
                newScheduleList.push(todo);
            }
        }

        // 日にちがある場合は予定リストに入れる
        if (date) {
            hypothesis.date = date;
            hypothesis.projectUuid = project.uuid;
            newScheduleList.push(hypothesis);
        }
        
        // 新しい予定リストを日付順にソート
        let sortNewScheduleList = newScheduleList.sort(function(a, b) {
            return (a.date < b.date) ? -1 : 1;  //オブジェクトの昇順ソート
        });
        state.scheduleList = sortNewScheduleList;
        console.info(state.scheduleList);
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