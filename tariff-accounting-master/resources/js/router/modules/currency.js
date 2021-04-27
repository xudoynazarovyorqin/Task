import Layout from '@/layouts/index'

export default [{
    path: '/currencies',
    component: Layout,
    redirect: '/currencies/index',
    children: [{
        path: '/currencies/index',
        component: () =>
            import ('@/pages/currency/index'),
        name: 'currencies.index',
        meta: { title: 'Валюта ', permission: 'currencies.index' }
    }]
}, ]