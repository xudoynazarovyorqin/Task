import Layout from '@/layouts/index'

export default [{
    path: '/users',
    component: Layout,
    redirect: '/users/index',
    children: [{
        path: '/users/index',
        component: () =>
            import ('@/pages/user/index'),
        name: 'users.index',
        meta: { title: 'Пользователи', permission: 'users.index' }
    }]
}, ]