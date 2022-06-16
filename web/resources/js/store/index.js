import Vue from "vue";
import Vuex from "vuex";

import auth from "./auth";
import form from "./form";
import error from "./error";
import onboarding from "./onboarding";
import project from "./project";
import todo from "./todo";
import initialize from "./initialize";
import navigation from "./navigation";

Vue.use(Vuex);

const store = new Vuex.Store({
    modules: {
        auth,
        form,
        error,
        onboarding,
        project,
        todo,
        initialize,
        navigation,
    },
});

export default store;
