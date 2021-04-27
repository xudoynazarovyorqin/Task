import Layout from '@/layouts/index'

export default [{
    path: '/levels',
    component: Layout,
    redirect: '/levels/index',
    children: [{
        path: '/levels/index',
        component: () =>
            import ('@/pages/level/index'),
        name: 'levels.index',
        meta: { title: 'Уровни ', permission: 'levels.index' }
    }, ]
}, ]