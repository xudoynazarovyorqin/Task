import Layout from '@/layouts/index'

export default [{
    path: '/warehouseMaterials',
    component: Layout,
    redirect: '/warehouseMaterials/index',
    children: [{
        path: '/warehouseMaterials/index',
        component: () =>
            import ('@/pages/warehouseMaterial/index'),
        name: 'warehouseMaterials.index',
        meta: { title: 'Склад сырья', permission: 'warehouseMaterials.index' }
    }, ]
}, ]