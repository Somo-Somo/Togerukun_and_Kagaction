const state = {
    user: null
  }

const getters = {
    check: state => !! state.user,
    username: state => state.user ? state.user.name : ''
}

const mutations = {
    setUser (state, user) {
      state.user = user
    }
  }

const actions = {
    register (context, data) {
      axios.get('/sanctum/csrf-cookie', { withCredentials: true })
        .then((res) =>{
            axios.post('/api/register', data)
                .then((response) => {
                    console.info('登録完了')
                    context.commit('setUser', response.data)
                })
                 .catch((err) => {
                    console.error('登録失敗')
                })
        })
        .catch(() => {
            console.warn('sanctum失敗')
        })
    },

    login (context, data) {
        axios.get('/sanctum/csrf-cookie', { withCredentials: true })
            .then((res) => {
                axios.post('/api/login', data)
                    .then((response) => {
                        console.info(response.data)
                        context.commit('setUser', response.data)
                    })
                    .catch((err) => {
                        console.error('ログイン失敗')
                    })
                })
            .catch((err) => {
                console.warn('sanctum失敗')
            })
    },

    async logout (context) {
            await axios.get('/sanctum/csrf-cookie', { withCredentials: true })
            await axios.post('/api/logout')
            console.info('ログアウト成功')
            await context.commit('setUser', null)
            console.info('userをnullにしました')
    },

    currentUser (context) {
        const response = axios.get('/api/auth_status',  { withCredentials: true })
        .then((response) => {
            console.log(response)
            const user = response.data
            context.commit('setUser', user)
        })
        .catch((errror) => {
            console.log(error)
        })
    }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}