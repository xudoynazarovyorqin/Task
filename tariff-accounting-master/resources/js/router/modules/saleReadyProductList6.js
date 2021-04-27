import Layout from '@/layouts/index'

export default [{
    path: '/saleReadyProductList6',
    component: Layout,
    redirect: '/saleReadyProductList6/index',
    children: [{
        path: '/saleReadyProductList6/index/:id',
        component: () =>
            import('@/pages/saleReadyProductList6/index'),
        name: 'saleReadyProductList6.index',
        meta: {
            title: 'Заказы готовой продукции',
            permission: 'saleReadyProductList6.index'
        }
    }]
}, ]
