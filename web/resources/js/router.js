import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './store';

// ページコンポーネントをインポートする
import User from './pages/User.vue';
import Login from './pages/Login.vue';
import HypothesisList from './pages/HypothesisList.vue';
import HypothesisDetail from './pages/HypothesisDetail.vue';
import Schedule from './pages/Schedule.vue';
import SystemError from './pages/errors/System.vue';
import NotFound from './pages/errors/NotFound.vue';

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
        next ('/setting');
      } else {
        console.info('ログインしてません')
        next ();
      }
    },
  },
  {
    path: '/setting',
    component: User,
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
    path: '/schedule',
    component: Schedule,
    name: "schedule"
  },
  {
    path: '/500',
    component: SystemError,
  },
  {
    path: '*',
    component: NotFound,
  },
  {
    path: '/project/*',
    component: NotFound,
  },
  {
    path: '/hypothesis/*',
    component: NotFound,
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
