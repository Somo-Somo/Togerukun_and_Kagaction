import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポートする
import User from './pages/User.vue'
import Login from './pages/Login.vue'
import Project from './pages/Project.vue'
import HypothesisList from './pages/HypothesisList.vue';
import HypothesisDetail from './pages/HypothesisDetail.vue';

// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter)

// パスとコンポーネントのマッピング
const routes = [
    {
        path: '/',
        component: User
    },
    {
        path: '/login',
        component: Login
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
]

// VueRouterインスタンスを作成する
const router = new VueRouter({
    mode: 'history',
    routes
})

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router