import Layout from '@/layouts/index'

export default [{
    path: '/tariffs',
    component: Layout,
    redirect: '/tariffs/index',
    children: [{
        path: '/tariffs/index',
        component: () =>
            import('@/pages/tariffs/index'),
        name: 'tariffs.index',
        meta: {
            title: 'Tарифы ',
            permission: 'tariffs.index'
        }
    }]
}, ]
