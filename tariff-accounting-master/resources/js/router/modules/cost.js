import Layout from '@/layouts/index'

export default [{
    path: '/costs',
    component: Layout,
    redirect: '/costs/index',
    children: [{
        path: '/costs/index',
        component: () =>
            import ('@/pages/cost/index'),
        name: 'costs.index',
        meta: { title: 'Типы расхода ', permission: 'costs.index' }
    }]
}, ]