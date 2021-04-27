import Layout from '@/layouts/index'

export default [{
    path: '/saleReadyProductList',
    component: Layout,
    redirect: '/saleReadyProductList/index',
    children: [{
        path: '/saleReadyProductList/index/:id',
        component: () =>
            import('@/pages/saleReadyProductList/index'),
        name: 'saleReadyProductList.index',
        meta: {
            title: 'Заказы готовой продукции',
            permission: 'saleReadyProductList.index'
        }
    }]
}, ]
