import Layout from '@/layouts/index'

export default [{
    path: '/warehouse',
    component: Layout,
    redirect: '/warehouse/purchased',
    children: [{
        path: 'index/:warehouse_id',
        component: () =>
            import ('@/pages/warehouseProduct/index'),
        name: 'warehouseProducts.index',
        meta: { title: 'Склад продукции', permission: 'warehouseProducts.index' }
    }]
}, ]