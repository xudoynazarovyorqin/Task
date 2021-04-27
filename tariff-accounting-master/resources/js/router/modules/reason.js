import Layout from '@/layouts/index'

export default [{
    path: '/reasons',
    component: Layout,
    redirect: '/reasons/index',
    children: [{
        path: '/reasons/index',
        component: () =>
            import ('@/pages/reason/index'),
        name: 'reasons.index',
        meta: { title: 'Причины ', permission: 'reasons.index' }
    }]
}, ]