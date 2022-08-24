import Vue from "vue";
import VueRouter from "vue-router";
import store from "./store";

// ページコンポーネントをインポートする
import Setting from "./pages/Setting.vue";
import Login from "./pages/Login.vue";
import Onboarding from "./pages/Onboarding.vue";
import TodoList from "./components/Views/TodoList/TodoList.vue";
// import TodoList from "./pages/TodoList.vue";
import TodoDetail from "./pages/TodoDetail.vue";
import Schedule from "./pages/Schedule.vue";
import SystemError from "./pages/errors/System.vue";
import NotFound from "./pages/errors/NotFound.vue";

// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter);

// パスとコンポーネントのマッピング
const routes = [
    {
        path: "/login",
        component: Login,
        beforeEnter(to, from, next) {
            if (store.getters["auth/check"]) {
                next("/setting");
            } else {
                next();
            }
        },
    },
    {
        path: "/onboarding",
        component: Onboarding,
    },
    {
        path: "/setting",
        component: Setting,
    },
    {
        path: "/project/:id",
        component: TodoList,
        name: "todoList",
    },
    {
        path: "/todo/:id",
        component: TodoDetail,
        name: "todoDetail",
    },
    {
        path: "/schedule",
        component: Schedule,
        name: "schedule",
    },
    {
        path: "/500",
        component: SystemError,
    },
    {
        path: "*",
        component: NotFound,
    },
    {
        path: "/project/*",
        component: NotFound,
    },
    {
        path: "/todo/*",
        component: NotFound,
    },
];

// VueRouterインスタンスを作成する
const router = new VueRouter({
    mode: "history",
    routes,
});

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router;
