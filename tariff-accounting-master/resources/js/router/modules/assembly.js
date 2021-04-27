import Layout from '@/layouts/index'

export default [{
    path: '/zborka',
    component: Layout,
    redirect: '/zborka/index',
    children: [{
        path: '/zborka/index',
        component: () =>
            import ('@/pages/assembly/index'),
        name: 'assembly.index',
        meta: { title: 'Сборкы', permission: 'assemblies.index' }
    }]
}, ]