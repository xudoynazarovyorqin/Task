import Layout from '@/layouts/index'

export default [{
    path: '/warehouseTypes',
    component: Layout,
    redirect: '/warehouseTypes/index',
    children: [{
        path: '/warehouseTypes/index',
        component: () =>
            import ('@/pages/warehouseType/index'),
        name: 'warehouseTypes.index',
        meta: { title: 'Тип склада', permission: 'warehouseTypes.index' }
    }]
}, ]