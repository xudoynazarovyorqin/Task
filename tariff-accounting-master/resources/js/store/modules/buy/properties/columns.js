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
    user_id: {
        show: false,
        title: i18n.t('message.user'),
        sortable: true,
        column: 'user_id'
    },
    provider_id: {
        show: true,
        title: i18n.t('message.provider'),
        sortable: true,
        column: 'provider_id'
    },
    contract_provider_id: {
        show: false,
        title: i18n.t('message.contract_number'),
        sortable: true,
        column: 'contract_provider_id'
    },
    materials: {
        show: true,
        title: i18n.t('message.materials'),
        sortable: false,
        column: 'materials'
    },
    paid: {
        show: false,
        title: i18n.t('message.paid_or'),
        sortable: true,
        column: 'paid'
    },
    date: {
        show: false,
        title: i18n.t('message.Delivery time'),
        sortable: true,
        column: 'date'
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
    status_id: {
        show: true,
        title: i18n.t('message.status'),
        sortable: true,
        column: 'status_id'
    },
    comment: {
        show: false,
        title: i18n.t('message.comment'),
        sortable: true,
        column: 'comment'
    },
    object_id: {
        show: false,
        title: i18n.t('message.Project №'),
        sortable: true,
        column: 'object_id'
    },
    object_type: {
        show: false,
        title: i18n.t('message.Project'),
        sortable: true,
        column: 'object_type'
    },
    is_warehouse: {
        show: false,
        title: i18n.t('message.for_warehouse'),
        sortable: true,
        column: 'is_warehouse'
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
    success: {
        show: true,
        title: i18n.t('message.coming'),
        sortable: false,
        column: 'success'
    },
    settings: {
        show: true,
        title: i18n.t('message.settings'),
        sortable: false,
        column: 'settings'
    },
};