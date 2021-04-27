import Layout from '@/layouts/index'

export default [{
    path: '/materials',
    component: Layout,
    redirect: '/materials/index',
    children: [{
        path: '/materials/index',
        component: () =>
            import ('@/pages/material/index'),
        name: 'materials.index',
        meta: { title: 'Сырья', permission: 'materials.index' }
    }]
}, ]