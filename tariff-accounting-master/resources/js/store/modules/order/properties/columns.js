import { i18n } from '@/utils/modules/i18n';

export const columns = {
    id: {
        show: true,
        title: i18n.t('message.n'),
        sortable: true,
        column: 'id'
    },
    datetime: {
        show: true,
        title: i18n.t('message.datetime'),
        sortable: true,
        column: 'datetime'
    },
    client_id: {
        show: true,
        title: i18n.t('message.client'),
        sortable: true,
        column: 'client_id'
    },
    begin_date: {
        show: false,
        title: i18n.t('message.Begin date'),
        sortable: true,
        column: 'begin_date'
    },
    end_date: {
        show: false,
        title: i18n.t('message.End date'),
        sortable: true,
        column: 'end_date'
    },
    contract_client_id: {
        show: false,
        title: i18n.t('message.contract_number'),
        sortable: true,
        column: 'contract_client_id'
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
    not_paid: {
        show: true,
        title: i18n.t('message.not_paid'),
        sortable: false,
        column: 'not_paid'
    },
    state_id: {
        show: true,
        title: i18n.t('message.status'),
        sortable: true,
        column: 'state_id'
    },
    priority_id: {
        show: false,
        title: i18n.t('message.priority'),
        sortable: true,
        column: 'priority_id'
    },
    created_at: {
        show: false,
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
    production_type: {
        show: true,
        title: i18n.t('message.Type production'),
        sortable: true,
        column: 'production_type'
    },
    settings: {
        show: true,
        title: i18n.t('message.settings'),
        sortable: false,
        column: 'settings'
    },
};