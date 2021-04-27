import Layout from '@/layouts/index'

export default [{
    path: '/saleReadyProductList3',
    component: Layout,
    redirect: '/saleReadyProductList3/index',
    children: [{
        path: '/saleReadyProductList3/index/:id',
        component: () =>
            import('@/pages/saleReadyProductList3/index'),
        name: 'saleReadyProductList3.index',
        meta: {
            title: 'Заказы готовой продукции',
            permission: 'saleReadyProductList3.index'
        }
    }]
}, ]
