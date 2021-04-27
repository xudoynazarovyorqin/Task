import Layout from '@/layouts/index'

export default [{
    path: '/quarters',
    component: Layout,
    redirect: '/quarters/index',
    children: [{
        path: '/quarters/index',
        component: () =>
            import('@/pages/quarter/index'),
        name: 'quarters.index',
        meta: {
            title: 'Кварталы',
            permission: 'quarters.index'
        }
    }]
}, ]
