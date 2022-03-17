import {OK, CREATED, UNAUTHORIZED, UNPROCESSABLE_ENTITY} from '../util';

const state = {
  user: null,
  apiStatus: null,
  loginErrorMessages: null,
  registerErrorMessages:  null,
};

const getters = {
  check: state => !!state.user,
  apiStatus: state => state.apiStatus,
  user: state => state.user ? state.user : '',
  registerErrorMessages: state => state.registerErrorMessages,
  loginErrorMessages: state => state.loginErrorMessages,
};

const mutations = {
  setUser (state, user) {
    state.user = user;
  },
  setApiStatus (state, status) {
    state.apiStatus = status;
  },
  setRegisterErrorMessages (state, messages) {
    state.registerErrorMessages = messages;
  },
  setLoginErrorMessages (state, messages) {
    state.loginErrorMessages = messages;
  },
};

const actions = {
  async register (context, data) {
    context.commit ('setApiStatus', null);
    await axios.get ('/sanctum/csrf-cookie', {withCredentials: true});
    const response = await axios.post ('/api/register', data)

    if (response.status === CREATED) {
      context.commit ('setApiStatus', true);
      return;
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
      return false;
    }

    context.commit ('setApiStatus', false);
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit ('setLoginErrorMessages', response.data.errors);
    } else if (response.status === UNAUTHORIZED) {
      const messeages = {
        email: [response.data.errors], 
        password: [response.data.errors]
      }
      context.commit ('setLoginErrorMessages', messeages);
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
    const user = typeof(response.data.id) ? response.data : null;
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
