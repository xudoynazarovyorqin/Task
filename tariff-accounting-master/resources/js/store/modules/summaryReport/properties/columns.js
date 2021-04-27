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
        show: true,
        title: i18n.t('message.total_amount'),
        sortable: true,
        column: 'amount'
    },
    object_name: {
        show: true,
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
    object_street: {
        show: false,
        title: i18n.t('message.street'),
        sortable: true,
        column: 'object_street'
    },
    object_home: {
        show: false,
        title: i18n.t('message.home'),
        sortable: true,
        column: 'object_home'
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
        column: 'object_flat'
    },
    payment_system: {
        show: true,
        title: i18n.t('message.payment_system'),
        sortable: true,
        column: 'payment_system'
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
};
