import Layout from '@/layouts/index'

export default [{
    path: '/roles',
    component: Layout,
    redirect: '/roles/index',
    children: [{
        path: '/roles/index',
        component: () =>
            import ('@/pages/role/index'),
        name: 'roles.index',
        meta: { title: 'Роли', permission: 'roles.index' }
    }, ]
}, ]