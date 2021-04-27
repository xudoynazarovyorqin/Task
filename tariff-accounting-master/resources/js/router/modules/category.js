import Layout from '@/layouts/index'

export default [{
    path: '/categories',
    component: Layout,
    redirect: '/categories/index',
    children: [{
        path: '/categories/index',
        component: () =>
            import ('@/pages/category/index'),
        name: 'categories.index',
        meta: { title: 'Группы ', permission: 'categories.index' }
    }]
}, ]