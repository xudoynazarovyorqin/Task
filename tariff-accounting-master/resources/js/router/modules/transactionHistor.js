import Layout from '@/layouts/index'

export default [{
    path: '/transactionHistor',
    component: Layout,
    redirect: '/transactionHistor/index',
    children: [{
        path: '/transactionHistor/index',
        component: () =>
            import('@/pages/transactionHistor/index'),
        name: 'transactionHistor.index',
        meta: {
            title: 'Tарифы ',
            permission: 'transactionHistor.index'
        }
    }]
}, ]
