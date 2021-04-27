import Layout from '@/layouts/index'
import { i18n } from '@/utils/modules/i18n';

export default [{
    path: '/realizations',
    component: Layout,
    redirect: '/realizations/index',
    children: [{
        path: '/realizations/index',
        component: () =>
            import ('@/pages/realization/index'),
        name: 'realizations.index',
        meta: { title: i18n.t('message.Realizations'), permission: 'realizations.index' }
    }]
}, ]