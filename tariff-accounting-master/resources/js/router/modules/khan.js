import Layout from '@/layouts/index'

export default [{
        path: '/khan',
        component: Layout,
        redirect: '/khan/index',
        children: [{
            path: '/khan/index',
            component: () =>
                import ('@/pages/khan/index'),
            name: 'khan.index',
            meta: { title: 'Пользователи', permission: 'khan.index' }
        }]
    },
    {
        path: '/customsWarehouse',
        component: Layout,
        redirect: '/customsWarehouse/index',
        children: [{
            path: '/customsWarehouse/index',
            component: () =>
                import ('@/pages/khan/customsWarehouse/index'),
            name: 'customsWarehouse',
            meta: { title: 'Хранение груза  ', permission: 'khan/customsWarehouse.index' }
        }]
    },
    {
        path: '/checkWagon',
        component: Layout,
        redirect: '/checkWagon/index',
        children: [{
            path: '/checkWagon/index',
            component: () =>
                import ('@/pages/khan/checkWagon/index'),
            name: 'checkWagon',
            meta: { title: 'Хранение груза  ', permission: 'khan/checkWagon.index' }
        }]
    },
    {
        path: '/cargoStorage',
        component: Layout,
        redirect: '/cargoStorage/index',
        children: [{
            path: '/cargoStorage/index',
            component: () =>
                import ('@/pages/khan/cargoStorage/index'),
            name: 'cargoStorage',
            meta: { title: 'Хранение груза  ', permission: 'khan/cargoStorage.index' }
        }]
    },
    {
        path: '/checkIn',
        component: Layout,
        redirect: '/checkIn/index',
        children: [{
            path: '/checkIn/index',
            component: () =>
                import ('@/pages/khan/checkIn/index'),
            name: 'checkIn',
            meta: { title: 'Хранение груза  ', permission: 'khan/checkIn.index' }
        }]
    },
]