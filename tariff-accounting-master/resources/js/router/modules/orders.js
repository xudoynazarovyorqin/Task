import Layout from '@/layouts/index'

export default [{
    path: '/orders',
    component: Layout,
    redirect: '/orders/index',
    children: [{
        path: '/orders/index',
        component: () =>
            import ('@/pages/orders/index'),
        name: 'orders.index',
        meta: { title: 'Заказы на производство', permission: 'orders.index' }
    }]
}, ]