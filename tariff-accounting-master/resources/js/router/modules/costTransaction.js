import Layout from '@/layouts/index'

export default [{
    path: '/costTransactions',
    component: Layout,
    redirect: '/costTransactions/index',
    children: [{
        path: '/costTransactions/index',
        component: () =>
            import ('@/pages/costTransaction/index'),
        name: 'costTransactions.index',
        meta: { title: 'Платежи за расходы', permission: 'costTransactions.index' }
    }]
}, ]