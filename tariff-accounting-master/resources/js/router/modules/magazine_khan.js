import Layout from '@/layouts/index'

export default [{
    path: '/magazine_khan',
    component: Layout,
    redirect: '/magazine_khan/index',
    children: [{
        path: '/magazine_khan/index',
        component: () =>
            import('@/pages/magazine_khan/index'),
        name: 'magazine_khan.index',
        meta: {
            title: 'Группа сотрудников',
            permission: 'magazine_khan.index'
        }
    }]
}, ]
