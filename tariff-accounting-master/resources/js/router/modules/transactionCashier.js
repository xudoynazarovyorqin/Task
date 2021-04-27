import Layout from '@/layouts/index'

export default [{
    path: '/transactionCashier',
    component: Layout,
    redirect: '/transactionCashier/index',
    children: [{
        path: '/transactionCashier/index',
        component: () =>
            import('@/pages/transactionCashier/index'),
        name: 'transactionCashier.index',
        meta: {
            title: 'Tарифы ',
            permission: 'transactionCashier.index'
        }
    }]
}, ]
