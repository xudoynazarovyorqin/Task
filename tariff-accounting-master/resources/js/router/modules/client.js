import Layout from '@/layouts/index'

export default [{
    path: '/clients',
    component: Layout,
    redirect: '/clients/index',
    children: [{
        path: '/clients/index',
        component: () =>
            import ('@/pages/client/index'),
        name: 'clients.index',
        meta: { title: 'Клиенты ', permission: 'clients.index' }
    }]
}, ]