import Layout from '@/layouts/index'

export default [{
    path: '/paymentTypes',
    component: Layout,
    redirect: '/paymentTypes/index',
    children: [{
        path: '/paymentTypes/index',
        component: () =>
            import ('@/pages/paymentType/index'),
        name: 'paymentTypes.index',
        meta: { title: 'Тип оплаты ', permission: 'paymentTypes.index' }
    }]
}, ]