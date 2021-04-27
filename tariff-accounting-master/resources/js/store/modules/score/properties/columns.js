import { i18n } from '@/utils/modules/i18n';

export const columns = {
    id: {
        show: true,
        title: i18n.t('message.n'),
        sortable: true,
        column: 'id'
    },
    active: {
        show: true,
        title: i18n.t('message.default'),
        sortable: false,
        column: 'active'
    },
    name: {
        show: true,
        title: i18n.t('message.name'),
        sortable: true,
        column: 'name'
    },
    branch_name: {
        show: false,
        title: i18n.t('message.branch_name'),
        sortable: true,
        column: 'branch_name'
    },
    currency_id: {
        show: true,
        title: i18n.t('message.currency'),
        sortable: true,
        column: 'currency_id'
    },
    mfo: {
        show: true,
        title: i18n.t('message.mfo'),
        sortable: true,
        column: 'mfo'
    },
    number: {
        show: true,
        title: i18n.t('message.number'),
        sortable: true,
        column: 'number'
    },
    incoming: {
        show: true,
        title: i18n.t('message.incoming'),
        sortable: true,
        column: 'incoming'
    },
    outgoing: {
        show: true,
        title: i18n.t('message.outgoing'),
        sortable: true,
        column: 'outgoing'
    },
    created_at: {
        show: true,
        title: i18n.t('message.created_at'),
        sortable: true,
        column: 'created_at'
    },
    updated_at: {
        show: false,
        title: i18n.t('message.updated_at'),
        sortable: true,
        column: 'updated_at'
    },
    settings: {
        show: true,
        title: i18n.t('message.settings'),
        sortable: false,
        column: 'settings'
    }
};