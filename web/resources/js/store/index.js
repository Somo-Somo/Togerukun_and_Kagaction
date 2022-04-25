import Vue from 'vue';
import Vuex from 'vuex';

import auth from './auth';
import form from './form';
import error from './error';
import project from './project';
import hypothesis from './hypothesis';
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
    hypothesis,
    initialize,
    navigation,
    schedule,
  },
});

export default store;
