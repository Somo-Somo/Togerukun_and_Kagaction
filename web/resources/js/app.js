import './bootstrap'
import Vue from 'vue'
import router from './router'
import store from './store'
import App from './App.vue'
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import '@mdi/font/css/materialdesignicons.css';
import 'material-design-icons-iconfont/dist/material-design-icons.css';

Vue.use(Vuetify);

new Vue({
    vuetify: new Vuetify(),
    el: '#app',
    router, 
    store,
    components: { App }, 
    template: '<App />'
})