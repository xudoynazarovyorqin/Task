import Layout from '@/layouts/index'

export default [{
    path: '/countries',
    component: Layout,
    redirect: '/countries/index',
    children: [{
        path: '/countries/index',
        component: () =>
            import ('@/pages/country/index'),
        name: 'countries.index',
        meta: { title: 'Страны ', permission: 'countries.index' }
    }]
}, ]