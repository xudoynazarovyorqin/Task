import {
    i18n
} from '@/utils/modules/i18n';

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
    user_id: {
        show: true,
        title: i18n.t('message.user'),
        sortable: true,
        column: 'user_id'
    },
    client_id: {
        show: true,
        title: i18n.t('message.client'),
        sortable: true,
        column: 'client_id'
    },
    contract_client_id: {
        show: true,
        title: i18n.t('message.contract_number'),
        sortable: true,
        column: 'contract_client_id'
    },
    total_price: {
        show: true,
        title: i18n.t('message.total_amount'),
        sortable: true,
        column: 'total_price'
    },
    paid_price: {
        show: true,
        title: i18n.t('message.paid'),
        sortable: true,
        column: 'paid_price'
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
    created_at: {
        show: true,
        title: i18n.t('message.created_at'),
        sortable: true,
        column: 'created_at'
    },
    updated_at: {
        show: true,
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
