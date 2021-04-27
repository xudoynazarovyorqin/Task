import Layout from '@/layouts/index'
import { i18n } from '@/utils/modules/i18n';

export default [{
    path: '/shipments',
    component: Layout,
    redirect: '/shipments/index',
    children: [{
        path: '/shipments/index',
        component: () =>
            import ('@/pages/shipment/index'),
        name: 'shipments.index',
        meta: { title: i18n.t('message.shipments'), permission: 'shipments.index' }
    }]
}, ]