import Layout from '@/layouts/index'

export default [{
    path: '/userTime',
    component: Layout,
    redirect: '/magazine_khan/userTime',
    children: [{
        path: '/magazine_khan/userTime',
        component: () =>
            import('@/pages/magazine_khan/userTime'),
        name: 'userTime',
        meta: {
            title: 'Группа сотрудников',
            permission: 'magazine_khan.userTime'
        }
    }]
}, ]
