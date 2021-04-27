import { i18n } from '@/utils/modules/i18n';

export const columns = {
    id: {
        show: true,
        title: i18n.t('message.n'),
        sortable: true,
        column: 'id'
    },
    datetime: {
        show: false,
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
    client_phone: {
        show: true,
        title: i18n.t('message.phone'),
        sortable: false,
        column: 'client_phone'
    },
    contract_client_id: {
        show: true,
        title: i18n.t('message.contract_number'),
        sortable: true,
        column: 'contract_client_id'
    },
    status_id: {
        show: true,
        title: i18n.t('message.status'),
        sortable: true,
        column: 'status_id'
    },
    console_number: {
        show: true,
        title: i18n.t('message.console_number'),
        sortable: true,
        column: 'console_number'
    },
    amount: {
        show: false,
        title: i18n.t('message.Monthly amount'),
        sortable: true,
        column: 'amount'
    },
    total_amount: {
        show: true,
        title: i18n.t('message.total_amount'),
        sortable: false,
        column: 'total_amount'
    },
    total_paid: {
        show: true,
        title: i18n.t('message.paid'),
        sortable: false,
        column: 'total_paid'
    },
    total_not_paid: {
        show: true,
        title: i18n.t('message.not_paid'),
        sortable: false,
        column: 'total_not_paid'
    },
    object_name: {
        show: false,
        title: i18n.t('message.name'),
        sortable: true,
        column: 'object_name'
    },
    district_id: {
        show: true,
        title: i18n.t('message.district'),
        sortable: true,
        column: 'district_id'
    },
    quarter_id: {
        show: false,
        title: i18n.t('message.quarter'),
        sortable: true,
        column: 'quarter_id'
    },
    object_address: {
        show: true,
        title: i18n.t('message.address'),
        sortable: false,
        column: 'object_address'
    },
    object_street: {
        show: false,
        title: i18n.t('message.street'),
        sortable: true,
        column: 'object_street',
        changeable: false
    },
    object_home: {
        show: false,
        title: i18n.t('message.home'),
        sortable: true,
        column: 'object_home',
        changeable: false
    },
    object_corps: {
        show: false,
        title: i18n.t('message.corps'),
        sortable: true,
        column: 'object_corps'
    },
    object_flat: {
        show: false,
        title: i18n.t('message.flat'),
        sortable: true,
        column: 'object_flat',
        changeable: false
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
    settings: {
        show: true,
        title: i18n.t('message.settings'),
        sortable: false,
        column: 'settings'
    },
};
