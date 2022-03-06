import Vue from 'vue';
import Vuex from 'vuex';

import auth from './auth';
import form from './form';
import error from './error';
import project from './project';
import hypothesis from './hypothesis';
import initialize from './initialize';

Vue.use (Vuex);

const store = new Vuex.Store ({
  modules: {
    auth,
    form,
    error,
    project,
    hypothesis,
    initialize
  },
});

export default store;
