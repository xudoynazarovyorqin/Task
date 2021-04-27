import Layout from '@/layouts/index'

export default [{
    path: '/defectProducts',
    component: Layout,
    redirect: '/defectProducts/index',
    children: [{
        path: '/defectProducts/index',
        component: () =>
            import ('@/pages/defectProducts/index'),
        name: 'defectProducts.index',
        meta: { title: 'Брак продукции ', permission: 'defectProducts.index' }
    }]
}, ]