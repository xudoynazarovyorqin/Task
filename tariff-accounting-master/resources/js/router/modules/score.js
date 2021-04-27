import Layout from '@/layouts/index'
import { i18n } from '@/utils/modules/i18n';

export default [{
    path: '/scores',
    component: Layout,
    redirect: '/scores/index',
    children: [{
        path: '/scores/index',
        component: () =>
            import ('@/pages/score/index'),
        name: 'scores.index',
        meta: { title: i18n.t('message.Scores'), permission: 'scores.index' }
    }]
}, ]