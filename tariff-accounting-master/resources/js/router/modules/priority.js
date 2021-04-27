import Layout from '@/layouts/index'

export default [{
    path: '/priority',
    component: Layout,
    redirect: '/priority/index',
    children: [{
        path: '/priority/index',
        component: () =>
            import ('@/pages/priority/index'),
        name: 'priority.index',
        meta: { title: 'Приоритеты ', permission: 'priority.index' }
    }]
}, ]