import Layout from '@/layouts/index'

export default [{
    path: '/report/materials',
    component: Layout,
    redirect: '/report/materials',
    children: [{
            path: '/report/materials',
            component: () =>
                import ('@/pages/report/materials'),
            name: 'reports.materials',
            meta: { title: 'Остаток сырья' }
        },
        {
            path: '/report/products',
            component: () =>
                import ('@/pages/report/products'),
            name: 'reports.products',
            meta: { title: 'Остаток сырья' }
        },
    ]
}, ]