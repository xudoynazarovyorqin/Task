import Layout from '@/layouts/index'

export default [{
    path: '/applicationParts',
    component: Layout,
    redirect: '/applicationParts/index',
    children: [{
        path: '/applicationParts/index',
        component: () =>
            import ('@/pages/applicationPart/index'),
        name: 'applicationParts.index',
        meta: { title: 'Платежи', permission: 'payments.index' }
    }]
}, ]
