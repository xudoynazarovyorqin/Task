import Layout from '@/layouts/index'

export default [{
    path: '/summaryReport',
    component: Layout,
    redirect: '/summaryReport/index',
    children: [{
        path: '/summaryReport/index',
        component: () =>
            import('@/pages/summaryReport/index'),
        name: 'summaryReport.index',
        meta: {
            title: 'Суммарный отчет',
            permission: 'summary_report.index'
        }
    }]
}, ]
