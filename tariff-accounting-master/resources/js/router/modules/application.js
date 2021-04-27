import Layout from '@/layouts/index'

export default [{
    path: '/applications',
    component: Layout,
    redirect: '/applications/index',
    children: [{
            path: '/applications/index',
            component: () => import('@/pages/application/index'),
            name: 'applications.index',
            meta: {
                title: 'Заявки',
                permission: 'applications.index'
            }
        },
        {
            path: '/applications/show/:id',
            component: () => import ('@/pages/application/show'),
            name: 'applications.show',
            meta: { title: 'Показать заявки', permission: 'applications.show' }
        },
        {
            path: '/applications/audits/:id',
            component: () => import ('@/pages/application/audits'),
            name: 'applications.audits',
            meta: { title: 'Журнал действий'}
        },
        {
            path: '/applications/parts/:id',
            component: () => import ('@/pages/application/parts'),
            name: 'applications.parts',
            meta: { title: 'История платежи'}
        },
        {
            path: '/applications/transactions/:id',
            component: () => import ('@/pages/application/transactions'),
            name: 'applications.transactions',
            meta: { title: 'История транзакций'}
        }
    ]
}, ]
