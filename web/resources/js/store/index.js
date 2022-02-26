import Vue from 'vue';
import Vuex from 'vuex';

import auth from './auth';
import form from './form';
import error from './error';
import project from './project';

Vue.use (Vuex);

const store = new Vuex.Store ({
  modules: {
    auth,
    form,
    error,
    project,
  },
});

export default store;
