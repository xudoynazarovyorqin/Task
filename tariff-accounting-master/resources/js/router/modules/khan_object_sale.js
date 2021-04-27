import Layout from '@/layouts/index'

export default [{
    path: '/khan_object_sale',
    component: Layout,
    redirect: '/khan_object_sale/index',
    children: [{
        path: '/khan_object_sale/index',
        component: () =>
            import('@/pages/khan_object_sale/index'),
        name: 'khan_object_sale',
        meta: {
            title: 'Пользователи',
            permission: 'khan_object_sale.index'
        }
    }]
}, ]
