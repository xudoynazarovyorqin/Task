import Layout from '@/layouts/index'
import {
    i18n
} from '@/utils/modules/i18n';

export default [{
    path: '/transactions',
    component: Layout,
    redirect: '/transactions/index',
    children: [{
        path: '/transactions/index',
        component: () =>
            import('@/pages/transaction/index'),
        name: 'transactions.index',
        meta: {
            title: i18n.t('message.transactions'),
            permission: 'transactions.index'
        }
    }]
}, ]
