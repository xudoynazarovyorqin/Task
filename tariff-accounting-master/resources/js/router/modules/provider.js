import Layout from '@/layouts/index'

export default [{
    path: '/providers',
    component: Layout,
    redirect: '/providers/index',
    children: [{
        path: '/providers/index',
        component: () =>
            import ('@/pages/provider/index'),
        name: 'providers.index',
        meta: { title: 'Поставщики ', permission: 'providers.index' }
    }]
}, ]