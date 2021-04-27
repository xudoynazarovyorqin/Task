import Layout from '@/layouts/index'

export default [{
    path: '/saleReadyProducts',
    component: Layout,
    redirect: '/saleReadyProducts/index',
    children: [{
        path: '/saleReadyProducts/index',
        component: () =>
            import ('@/pages/saleReadyProduct/index'),
        name: 'saleReadyProducts.index',
        meta: { title: 'Заказы готовой продукции', permission: 'saleReadyProducts.index' }
    }]
}, ]