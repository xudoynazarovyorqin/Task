import Layout from '@/layouts/index'

export default [{
    path: '/districts',
    component: Layout,
    redirect: '/districts/index',
    children: [{
        path: '/districts/index',
        component: () =>
            import('@/pages/district/index'),
        name: 'districts.index',
        meta: {
            title: 'Районы',
            permission: 'districts.index'
        }
    }]
}, ]
