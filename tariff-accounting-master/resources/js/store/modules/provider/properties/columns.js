import { i18n } from '@/utils/modules/i18n';

export const columns = {
    id: {
        show: true,
        title: i18n.t('message.n'),
        sortable: true,
        column: 'id'
    },
    name: {
        show: true,
        title: i18n.t('message.name'),
        sortable: true,
        column: 'name'
    },
    full_name: {
        show: false,
        title: i18n.t('message.Full name'),
        sortable: true,
        column: 'full_name'
    },
    balance: {
        show: true,
        title: i18n.t('message.balance'),
        sortable: true,
        column: 'balance'
    },
    phone: {
        show: true,
        title: i18n.t('message.phone'),
        sortable: true,
        column: 'phone'
    },
    email: {
        show: false,
        title: i18n.t('message.email'),
        sortable: true,
        column: 'email'
    },
    actual_address: {
        show: false,
        title: i18n.t('message.actual_address'),
        sortable: true,
        column: 'actual_address'
    },
    type_id: {
        show: false,
        title: i18n.t('message.Type of client'),
        sortable: true,
        column: 'type_id'
    },
    fax: {
        show: false,
        title: i18n.t('message.faxs'),
        sortable: true,
        column: 'fax'
    },
    sku: {
        show: true,
        title: i18n.t('message.code'),
        sortable: true,
        column: 'sku'
    },
    comment: {
        show: false,
        title: i18n.t('message.comment'),
        sortable: true,
        column: 'comment'
    },
    legal_address: {
        show: false,
        title: i18n.t('message.Legal address'),
        sortable: true,
        column: 'legal_address'
    },
    inn: {
        show: false,
        title: i18n.t('message.inn'),
        sortable: true,
        column: 'inn'
    },
    mfo: {
        show: false,
        title: i18n.t('message.mfo'),
        sortable: true,
        column: 'mfo'
    },
    okonx: {
        show: false,
        title: i18n.t('message.okonx'),
        sortable: true,
        column: 'okonx'
    },
    oked: {
        show: false,
        title: i18n.t('message.oked'),
        sortable: true,
        column: 'oked'
    },
    rkp_nds: {
        show: false,
        title: i18n.t('message.Rkp nds'),
        sortable: true,
        column: 'rkp_nds'
    },
    total_buy: {
        show: true,
        title: i18n.t('message.Total buys'),
        sortable: false,
        column: 'total_buy'
    },
    total_buy_paid: {
        show: true,
        title: i18n.t('message.paid'),
        sortable: false,
        column: 'total_buy_paid'
    },
    total_buy_not_paid: {
        show: false,
        title: i18n.t('message.not_paid'),
        sortable: false,
        column: 'total_buy_not_paid'
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
    }
};