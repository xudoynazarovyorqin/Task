import Layout from '@/layouts/index'

export default [{
    path: '/states',
    component: Layout,
    redirect: '/states/index',
    children: [{
        path: '/states/index',
        component: () =>
            import ('@/pages/state/index'),
        name: 'states.index',
        meta: { title: 'Статусы ', permission: 'states.index' }
    }]
}, ]