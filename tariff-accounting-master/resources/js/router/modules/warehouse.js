import Layout from '@/layouts/index'

export default [{
    path: '/warehouses',
    component: Layout,
    redirect: '/warehouses/index',
    children: [{
        path: '/warehouses/index',
        component: () =>
            import ('@/pages/warehouse/index'),
        name: 'warehouses.index',
        meta: { title: 'Склад', permission: 'warehouses.index' }
    }]
}, ]