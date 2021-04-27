import Layout from '@/layouts/index'

export default [{
    path: '/contractProviders',
    component: Layout,
    redirect: '/contractProviders/index',
    children: [{
        path: '/contractProviders/index',
        component: () =>
            import ('@/pages/contractProvider/index'),
        name: 'contractProviders.index',
        meta: { title: 'Договоры(Поставщиков)', permission: 'contractProviders.index' }
    }]
}, ]