import Layout from '@/layouts/index'

export default [{
    path: '/contractClients',
    component: Layout,
    redirect: '/contractClients/index',
    children: [{
        path: '/contractClients/index',
        component: () =>
            import ('@/pages/contractClient/index'),
        name: 'contractClients.index',
        meta: { title: 'Договоры(Клиентов)', permission: 'contractClients.index' }
    }]
}, ]