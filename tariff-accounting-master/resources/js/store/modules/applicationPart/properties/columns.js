import { i18n } from '@/utils/modules/i18n';

export const columns = {
    id: {
        show: true,
        title: i18n.t('message.n'),
        sortable: true,
        column: 'id'
    },
    client_id: {
        show: true,
        title: i18n.t('message.client'),
        sortable: true,
        column: 'client_id'
    },
    console_number: {
        show: true,
        title: i18n.t('message.console_number'),
        sortable: true,
        column: 'console_number'
    },
    application_id: {
        show: false,
        title: i18n.t('message.Number application'),
        sortable: true,
        column: 'application_id'
    },
    start_date: {
        show: true,
        title: i18n.t('message.start_date'),
        sortable: true,
        column: 'start_date'
    },
    stop_date: {
        show: true,
        title: i18n.t('message.stop_date'),
        sortable: true,
        column: 'stop_date'
    },
    amount: {
        show: true,
        title: i18n.t('message.total_amount'),
        sortable: true,
        column: 'amount'
    },
    paid: {
        show: true,
        title: i18n.t('message.paid'),
        sortable: true,
        column: 'paid'
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
    // settings: {
    //     show: true,
    //     title: i18n.t('message.settings'),
    //     sortable: false,
    //     column: 'settings'
    // },
};
