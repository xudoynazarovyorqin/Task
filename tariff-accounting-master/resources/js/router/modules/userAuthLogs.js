import Layout from '@/layouts/index'

export default [{
    path: '/userAuthLogs',
    component: Layout,
    redirect: '/userAuthLogs/index',
    children: [{
        path: '/userAuthLogs/index',
        component: () =>
            import ('@/pages/userAuthLog/index'),
        name: 'userAuthLogs.index',
        meta: { title: 'Действия ползавателя', permission: 'userAuthLogs.index' }
    }]
}, ]