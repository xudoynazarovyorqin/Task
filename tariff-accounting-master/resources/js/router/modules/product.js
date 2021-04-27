import Layout from '@/layouts/index'

export default [{
    path: '/products',
    component: Layout,
    redirect: '/products/index',
    children: [{
        path: '/products/index',
        component: () =>
            import ('@/pages/product/index'),
        name: 'products.index',
        meta: { title: 'Продукция', permission: 'products.index' }
    }]
}, ]