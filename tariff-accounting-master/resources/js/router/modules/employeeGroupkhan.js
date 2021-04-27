import Layout from '@/layouts/index'

export default [{
    path: '/employeeGroupkhan',
    component: Layout,
    redirect: '/employeeGroupkhan/index',
    children: [{
        path: '/employeeGroupkhan/index',
        component: () =>
            import('@/pages/employeeGroupkhan/index'),
        name: 'employeeGroupkhan.index',
        meta: {
            title: 'Группа сотрудников',
            permission: 'employeeGroupkhan.index'
        }
    }]
}, ]
