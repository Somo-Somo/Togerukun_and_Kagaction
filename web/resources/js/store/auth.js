import {OK, UNPROCESSABLE_ENTITY} from '../util';

const state = {
  user: null,
  apiStatus: null,
  loginErrorMessages: null,
};

const getters = {
  check: state => !!state.user,
  username: state => (state.user ? state.user.name : ''),
};

const mutations = {
  setUser (state, user) {
    state.user = user;
  },
  setApiStatus (state, status) {
    state.apiStatus = status;
  },
  setLoginErrorMessages (state, messages) {
    state.loginErrorMessages = messages;
  },
};

const actions = {
  register (context, data) {
    axios
      .get ('/sanctum/csrf-cookie', {withCredentials: true})
      .then (res => {
        axios
          .post ('/api/register', data)
          .then (response => {
            console.info ('登録完了');
            context.commit ('setUser', response.data);
          })
          .catch (err => {
            console.error ('登録失敗');
          });
      })
      .catch (() => {
        console.warn ('sanctum失敗');
      });
  },

  async login (context, data) {
    context.commit ('setApiStatus', null);
    await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
    const response = await axios
      .post ('/api/login', data)
      .catch (err => err.response || err);

    console.info (response.status);

    if (response.status === OK) {
      context.commit ('setApiStatus', true);
      context.commit ('setUser', response.data);
      console.info ('ログイン成功');
      return false;
    }

    console.log (response.status);

    context.commit ('setApiStatus', false);
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit ('setLoginErrorMessages', response.data.errors);
    } else {
      context.commit ('error/setCode', response.status, {root: true});
    }
  },

  async logout (context) {
    await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
    await axios.post ('/api/logout');
    console.info ('ログアウト成功');
    await context.commit ('setUser', null);
    console.info ('userをnullにしました');
  },

  currentUser (context) {
    const response = axios
      .get ('/api/auth_status', {withCredentials: true})
      .then (response => {
        console.log (response);
        const user = response.data;
        context.commit ('setUser', user);
      })
      .catch (errror => {
        console.log (error);
      });
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
