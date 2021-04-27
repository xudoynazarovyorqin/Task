import Layout from '@/layouts/index'

export default [{
    path: '/saleReadyProductList4',
    component: Layout,
    redirect: '/saleReadyProductList4/index',
    children: [{
        path: '/saleReadyProductList4/index/:id',
        component: () =>
            import('@/pages/saleReadyProductList4/index'),
        name: 'saleReadyProductList4.index',
        meta: {
            title: 'Заказы готовой продукции',
            permission: 'saleReadyProductList4.index'
        }
    }]
}, ]
