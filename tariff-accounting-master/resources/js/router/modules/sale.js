import Layout from '@/layouts/index'

export default [{
    path: '/sales',
    component: Layout,
    redirect: '/sales/index',
    children: [{
        path: '/sales/index',
        component: () =>
            import ('@/pages/sale/index'),
        name: 'sales.index',
        meta: { title: 'Заказы производство', permission: 'sales.index' }
    }]
}, ]