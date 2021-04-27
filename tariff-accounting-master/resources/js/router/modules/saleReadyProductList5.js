import Layout from '@/layouts/index'

export default [{
    path: '/saleReadyProductList5',
    component: Layout,
    redirect: '/saleReadyProductList5/index',
    children: [{
        path: '/saleReadyProductList5/index/:id',
        component: () =>
            import('@/pages/saleReadyProductList5/index'),
        name: 'saleReadyProductList5.index',
        meta: {
            title: 'Заказы готовой продукции',
            permission: 'saleReadyProductList5.index'
        }
    }]
}, ]
