import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポートする
import Login from './pages/Login.vue'
import Project from './pages/Project.vue'
import HypothesisList from './pages/HypothesisList.vue';

// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter)

// パスとコンポーネントのマッピング
const routes = [
    {
        path: '/',
        component: Login
    },
    {
        path: '/project',
        component: Project
    },
    {
        path: '/project/:id',
        component: HypothesisList
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