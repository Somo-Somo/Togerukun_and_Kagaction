import Vue from 'vue';
import Vuex from 'vuex';

import auth from './auth';
import form from './form';
import error from './error';
import project from './project';
import todo from './todo';
import initialize from './initialize';
import navigation from './navigation';
import schedule from './schedule';

Vue.use (Vuex);

const store = new Vuex.Store ({
  modules: {
    auth,
    form,
    error,
    project,
    todo,
    initialize,
    navigation,
    schedule,
  },
});

export default store;
