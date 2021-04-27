import Layout from '@/layouts/index'

export default [{
    path: '/saleReadyProductList2',
    component: Layout,
    redirect: '/saleReadyProductList2/index',
    children: [{
        path: '/saleReadyProductList2/index/:id',
        component: () =>
            import('@/pages/saleReadyProductList2/index'),
        name: 'saleReadyProductList2.index',
        meta: {
            title: 'Заказы готовой продукции',
            permission: 'saleReadyProductList2.index'
        }
    }]
}, ]
