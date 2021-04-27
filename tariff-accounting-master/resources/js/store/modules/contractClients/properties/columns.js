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
    remainder: {
        show: true,
        title: i18n.t('message.balance'),
        sortable: true,
        column: 'remainder'
    },
    // paid: {
    //     show: true,
    //     title: i18n.t('message.paid'),
    //     sortable: true,
    //     column: 'paid'
    // },
    client_id: {
        show: true,
        title: i18n.t('message.client'),
        sortable: true,
        column: 'client_id'
    },
    status_id: {
        show: true,
        title: i18n.t('message.status'),
        sortable: true,
        column: 'status_id'
    },
    conclusion_date: {
        show: true,
        title: i18n.t('message.conclusion_date'),
        sortable: true,
        column: 'conclusion_date'
    },
    termination_date: {
        show: true,
        title: i18n.t('message.termination_date'),
        sortable: true,
        column: 'termination_date'
    },
    comment: {
        show: false,
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
