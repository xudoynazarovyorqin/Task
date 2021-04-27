import Layout from '@/layouts/index'

export default [
    {
    path: '/khan_objects',
    component: Layout,
    redirect: '/khan_objects/index',
    children: [{
        path: '/khan_objects/index',
        component: () =>
            import ('@/pages/khan_objects/index'),
        name: 'khan_objects',
        meta: { title: 'Пользователи', permission: 'khan_objects.index' }
    }]
}, 
{
    path: '/editHouseObjects',
    component: Layout,
    redirect: '/editHouseObjects/index',
    children: [
        {
            path: '/editHouseObjects/index',
            component: () => import('./../../pages/khan_objects/editHouseObjects/index'),
            name: 'editHouseObjects',
            meta: { title: 'Хранение груза  ',permission: '/khan_objects/editHouseObjects/index' }
        }
    ]
}, 
{
    path: '/editHouse',
    component: Layout,
    redirect: '/editHouse/index',
    children: [
        {
            path: '/editHouse/index',
            component: () => import('./../../pages/khan_objects/editHouse/index'),
            name: 'editHouse',
            meta: { title: 'Хранение груза  ',permission: '/khan_objects/editHouse/index' }
        }
    ]
}, 
{
    path: '/chessPlayer',
    component: Layout,
    redirect: '/chessPlayer/index',
    children: [
        {
            path: '/chessPlayer/index',
            component: () => import('./../../pages/khan_objects/chessPlayer/index.vue'),
            name: 'chessPlayer',
            meta: { title: 'Хранение груза  ',permission: '/khan_objects/chessPlayer/index' }
        }
    ]
}, 
{
    path: '/floorPlans',
    component: Layout,
    redirect: '/floorPlans/index',
    children: [
        {
            path: '/floorPlans/index',
            component: () => import('./../../pages/khan_objects/floorPlans/index'),
            name: 'floorPlans',
            meta: { title: 'Хранение груза  ',permission: '/khan_objects/floorPlans/index' }
        }
    ]
},
{
    path: '/floors',
    component: Layout,
    redirect: '/floors/index',
    children: [
        {
            path: '/floors/index',
            component: () => import('./../../pages/khan_objects/floors/index'),
            name: 'floors',
            meta: { title: 'Хранение груза  ',permission: '/khan_objects/floors/index' }
        }
    ]
},
{
    path: '/facades',
    component: Layout,
    redirect: '/facades/index',
    children: [
        {
            path: '/facades/index',
            component: () => import('./../../pages/khan_objects/facades/index'),
            name: 'facades',
            meta: { title: 'Хранение груза  ',permission: '/khan_objects/facades/index' }
        }
    ]
}, 
]