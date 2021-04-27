import Layout from '@/layouts/index'

export default [{
    path: '/buyNotifications',
    component: Layout,
    redirect: '/buyNotifications/index',
    children: [{
        path: '/buyNotifications/index',
        component: () =>
            import ('@/pages/buyNotification/index'),
        name: 'buyNotifications.index',
        meta: { title: 'Сыря(не хватает)' }
    }, ]
}, ]