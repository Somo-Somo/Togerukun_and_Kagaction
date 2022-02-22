import {OK, CREATED, UNPROCESSABLE_ENTITY} from '../util';

const state = {
  user: null,
  apiStatus: null,
  loginErrorMessages: null,
  registerErrorMessages: null,
};

const getters = {
  check: state => !!state.user,
  username: state => (state.user ? state.user.name : ''),
};

const mutations = {
  setUser (state, user) {
    state.user = user;
    console.info(typeof(state.user))
    console.info(state.user)
  },
  setApiStatus (state, status) {
    state.apiStatus = status;
  },
  setLoginErrorMessages (state, messages) {
    state.loginErrorMessages = messages;
  },
  setRegisterErrorMessages (state, messages) {
    state.registerErrorMessages = messages;
  },
};

const actions = {
  async register (context, data) {
    context.commit ('setApiStatus', null);
    await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
    const response = await axios.post ('/api/register', data);

    if (response.status === CREATED) {
      context.commit ('setApiStatus', true);
      context.commit ('setUser', response.data);
      console.info ('会員登録成功');
      return false;
    }

    context.commit ('setApiStatus', false);

    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit ('setRegisterErrorMessages', response.data.errors);
    } else {
      context.commit ('error/setCode', response.status, {root: true});
    }
  },

  async login (context, data) {
    context.commit ('setApiStatus', null);
    await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
    const response = await axios.post ('/api/login', data);

    if (response.status === OK) {
      context.commit ('setApiStatus', true);
      context.commit ('setUser', response.data);
      console.info ('ログイン成功');
      return false;
    }

    context.commit ('setApiStatus', false);
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit ('setLoginErrorMessages', response.data.errors);
    } else {
      context.commit ('error/setCode', response.status, {root: true});
    }
  },

  async logout (context) {
    await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
    const response = await axios.post ('/api/logout');

    if (response.status === OK) {
      context.commit ('setApiStatus', true);
      context.commit ('setUser', null);
      return false;
    }

    context.commit ('setApiStatus', false);
    context.commit ('error/setCode', response.status, {root: true});
  },

  async currentUser (context) {
    context.commit ('setApiStatus', null);
    await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
    const response = await axios.get('/api/auth_status');
    console.info(response.data)
    const user = typeof(response.data) === "object" ? response.data : null;

    if (response.status === OK) {
      context.commit ('setApiStatus', true);
      context.commit ('setUser', user);
      return false;
    }

    context.commit ('setApiStatus', false);
    context.commit ('error/setCode', response.status, {root: true});
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
