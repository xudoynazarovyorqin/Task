import Vue from 'vue'
import VueRouter from 'vue-router'
import Layout from '@/layouts/index'

Vue.use(VueRouter);

let routes = [{
        path: '/login',
        component: () =>
            import ('@/pages/login/index'),
        hidden: true
    },
    {
        path: '/404',
        component: () =>
            import ('@/pages/error-page/404'),
        hidden: true
    },
    {
        path: '/401',
        component: () =>
            import ('@/pages/error-page/401'),
        hidden: true
    },
    {
        path: '/',
        component: Layout,
        redirect: '/',
        children: [{
            path: '/',
            component: () =>
                import ('@/pages/home'),
            name: 'home',
            meta: { title: 'Главная страница ' }
        }]
    },
    {
        path: '/settings',
        component: Layout,
        redirect: '/',
        children: [{
            path: '/',
            component: () =>
                import ('@/pages/setting/setting'),
            name: 'settings',
            meta: { title: 'Настройки ' }
        }]
    },
]

/**
 * Load all routes from modulea folder
 */
const request = require.context('./modules', true, /.js/);

request.keys().map(module => {
    routes = routes.concat(request(module).default);
});

const router = new VueRouter({
    mode: 'history',
    // history: true,
    // hashbang: false,
    routes: routes
})
router.mode = 'html5'

export default router;