import Layout from '@/layouts/index'

export default [{
    path: '/permissions',
    component: Layout,
    redirect: '/permissions/index',
    children: [{
        path: '/permissions/index',
        component: () =>
            import ('@/pages/permission/index'),
        name: 'permissions.index',
        meta: { title: 'Права доступа', permission: 'permissions.index' }
    }]
}, ]