import Layout from '@/layouts/index';
import {i18n} from '@/utils/modules/i18n';

export default [{
    path: '/buyReadyProducts',
    component: Layout,
    redirect: '/buyReadyProducts/index',
    children: [{
        path: '/buyReadyProducts/index',
        component: () =>
            import ('@/pages/buyReadyProduct/index'),
        name: 'buyReadyProducts.index',
        meta: { title: i18n.t('message.buy_ready_products'), permission: 'buyReadyProducts.index' }
    }]
}, ]