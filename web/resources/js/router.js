import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './store';

// ページコンポーネントをインポートする
import User from './pages/User.vue';
import Login from './pages/Login.vue';
import Project from './pages/Project.vue';
import HypothesisList from './pages/HypothesisList.vue';
import HypothesisDetail from './pages/HypothesisDetail.vue';
import SystemError from './pages/errors/System.vue';

// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use (VueRouter);

// パスとコンポーネントのマッピング
const routes = [
  {
    path: '/login',
    component: Login,
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        console.info('ログインしてます')
        next ('/user');
      } else {
        console.info('ログインしてません')
        next ();
      }
    },
  },
  {
    path: '/user',
    component: User,
  },
  {
    path: '/projects',
    component: Project,
    name: "project"
  },
  {
    path: '/project/:id',
    component: HypothesisList,
    name: "hypothesisList"
  },
  {
    path: '/hypothesis/:id',
    component: HypothesisDetail,
    name: "hypothesisDetail"
  },
  {
    path: '/500',
    component: SystemError,
  },
];

// VueRouterインスタンスを作成する
const router = new VueRouter ({
  mode: 'history',
  routes,
});

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router;
