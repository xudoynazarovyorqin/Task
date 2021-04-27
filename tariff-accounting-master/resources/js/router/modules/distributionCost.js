import Layout from '@/layouts/index'
import { i18n } from '@/utils/modules/i18n';

export default [{
    path: '/distributionCosts',
    component: Layout,
    redirect: '/distributionCosts/index',
    children: [{
        path: '/distributionCosts/index',
        component: () =>
            import ('@/pages/distributionCost/index'),
        name: 'distributionCosts.index',
        meta: { title: i18n.t('message.Distribution of costs'), permission: 'distribution_costs.index' }
    }]
}, ]