import Layout from '@/layouts/index'

export default [{
    path: '/buys',
    component: Layout,
    redirect: '/buys/index',
    children: [{
        path: '/buys/index',
        component: () =>
            import ('@/pages/buy/index'),
        name: 'buys.index',
        meta: { title: 'Закупки сырья', permission: 'buys.index' }
    }]
}, ]