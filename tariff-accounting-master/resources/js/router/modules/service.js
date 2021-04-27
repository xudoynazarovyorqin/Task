import Layout from '@/layouts/index'

export default [{
    path: '/services',
    component: Layout,
    redirect: '/services/index',
    children: [{
        path: '/services/index',
        component: () =>
            import('@/pages/service/index'),
        name: 'services.index',
        meta: {
            title: 'Tарифы',
            permission: 'services.index'
        }
    }]
}, ]
