import Layout from '@/layouts/index'

export default [{
    path: '/employee_khan',
    component: Layout,
    redirect: '/employee_khan/index',
    children: [{
        path: '/employee_khan/index',
        component: () =>
            import('@/pages/employee_khan/index'),
        name: 'employee_khan.index',
        meta: {
            title: 'Группа сотрудников',
            permission: 'employee_khan.index'
        }
    }]
}, ]
