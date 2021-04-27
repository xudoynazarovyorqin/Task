import Layout from '@/layouts/index'

export default [{
    path: '/audits',
    component: Layout,
    redirect: '/audits/index',
    children: [{
        path: '/audits/index',
        component: () =>
            import ('@/pages/audit/index'),
        name: 'audits.index',
        meta: { title: 'Журнал ', permission: 'audits.index' }
    }, ]
}, ]