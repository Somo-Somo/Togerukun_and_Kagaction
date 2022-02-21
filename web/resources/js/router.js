import Vue from 'vue'
import VueRouter from 'vue-router'
import store from './store'

// ページコンポーネントをインポートする
import User from './pages/User.vue'
import Login from './pages/Login.vue'
import Project from './pages/Project.vue'
import HypothesisList from './pages/HypothesisList.vue';
import HypothesisDetail from './pages/HypothesisDetail.vue';
import SystemError from './pages/errors/System.vue'

// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter)

// パスとコンポーネントのマッピング
const routes = [
    {
        path: '/user_test',
        component: User
    },
    {
        path: '/login',
        component: Login,
        beforeEnter (to, from, next) {
            if (store.getters['auth/check']) {
              next('/user_test')
            } else {
              next()
            }
        }
    },
    {
        path: '/projects',
        component: Project
    },
    {
        path: '/projects/:id',
        component: HypothesisList
    },
    {
        path: '/projects/:id/:detailId',
        component: HypothesisDetail
    },
    {
        path: '/500',
        component: SystemError
    }
]

// VueRouterインスタンスを作成する
const router = new VueRouter({
    mode: 'history',
    routes
})

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router