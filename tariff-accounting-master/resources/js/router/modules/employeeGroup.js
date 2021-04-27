import Layout from '@/layouts/index'

export default [{
    path: '/employeeGroup',
    component: Layout,
    redirect: '/employeeGroup/index',
    children: [{
        path: '/employeeGroup/index',
        component: () =>
            import ('@/pages/employeeGroup/index'),
        name: 'employeeGroups.index',
        meta: {
            title: 'Группа сотрудников',
            permission: 'employeeGroup.index'
        }
    }]
}, ]