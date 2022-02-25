import Vue from 'vue';
import Vuex from 'vuex';

import auth from './auth';
import form from './form';
import error from './error';

Vue.use (Vuex);

const store = new Vuex.Store ({
  modules: {
    auth,
    form,
    error,
  },
});

export default store;
