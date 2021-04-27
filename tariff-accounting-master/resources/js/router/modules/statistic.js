import Layout from '@/layouts/index'

export default [{
    path: '/statistics/index',
    component: Layout,
    redirect: '/statistics/index',
    children: [{
        path: '/statistics/index',
        component: () =>
            import ('@/pages/statistic/index'),
        name: 'statistics.index',
        meta: { title: 'Статистика', permission: 'statistics.index' }
    }]
}, ]