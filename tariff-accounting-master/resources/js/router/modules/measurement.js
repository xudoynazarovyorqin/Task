import Layout from '@/layouts/index'

export default [{
    path: '/measurements',
    component: Layout,
    redirect: '/measurements/index',
    children: [{
        path: '/measurements/index',
        component: () =>
            import ('@/pages/measurement/index'),
        name: 'measurements.index',
        meta: { title: 'Ед. изм.', permission: 'measurements.index' }
    }]
}, ]