import { i18n } from '@/utils/modules/i18n';

export const columns = {
    id: {
        show: true,
        title: i18n.t('message.n'),
        sortable: true,
        column: 'id'
    },
    number: {
        show: true,
        title: i18n.t('message.contract') + ' ' + i18n.t('message.n'),
        sortable: true,
        column: 'number'
    },
    parent_id: {
        show: false,
        title: i18n.t('message.Parent contract'),
        sortable: true,
        column: 'parent_id'
    },
    begin_date: {
        show: true,
        title: i18n.t('message.date'),
        sortable: true,
        column: 'begin_date'
    },
    sum: {
        show: true,
        title: i18n.t('message.Sum'),
        sortable: true,
        column: 'sum'
    },
    paid: {
        show: true,
        title: i18n.t('message.paid'),
        sortable: true,
        column: 'paid'
    },
    provider_id: {
        show: true,
        title: i18n.t('message.provider'),
        sortable: true,
        column: 'provider_id'
    },
    status_id: {
        show: true,
        title: i18n.t('message.status'),
        sortable: true,
        column: 'status_id'
    },
    comment: {
        show: true,
        title: i18n.t('message.comment'),
        sortable: true,
        column: 'comment'
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