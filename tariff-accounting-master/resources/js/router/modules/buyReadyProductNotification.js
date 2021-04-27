import Layout from '@/layouts/index'

export default [{
    path: '/buyReadyProductNotifications',
    component: Layout,
    redirect: '/buyReadyProductNotifications/index',
    children: [{
        path: '/buyReadyProductNotifications/index',
        component: () =>
            import ('@/pages/buyReadyProductNotification/index'),
        name: 'buyReadyProductNotifications.index',
        meta: { title: 'Продукты(не хватает)' }
    }, ]
}, ]