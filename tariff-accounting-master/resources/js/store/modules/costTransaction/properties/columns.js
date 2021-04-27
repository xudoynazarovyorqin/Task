import { i18n } from '@/utils/modules/i18n';

export const columns = {
    id: {
        show: true,
        title: i18n.t('message.n'),
        sortable: true,
        column: 'id'
    },
    debit: {
        show: true,
        title: i18n.t('message.type_document'),
        sortable: true,
        column: 'debit'
    },
    payment_type_id: {
        show: true,
        title: i18n.t('message.payment_type'),
        sortable: true,
        column: 'payment_type_id'
    },
    datetime: {
        show: true,
        title: i18n.t('message.datetime'),
        sortable: true,
        column: 'datetime'
    },
    transactionable_id: {
        show: true,
        title: i18n.t('message.cost'),
        sortable: true,
        column: 'transactionable_id'
    },
    amount: {
        show: true,
        title: i18n.t('message.Amount'),
        sortable: true,
        column: 'amount'
    },
    currency_id: {
        show: true,
        title: i18n.t('message.currency'),
        sortable: true,
        column: 'currency_id'
    },
    rate: {
        show: false,
        title: i18n.t('message.rate'),
        sortable: false,
        column: 'rate',
        changeable: false,
    },
    comment: {
        show: false,
        title: i18n.t('message.comment'),
        sortable: true,
        column: 'comment'
    },
    distribution_amount: {
        show: true,
        title: i18n.t('message.distribution_amount'),
        sortable: true,
        column: 'distribution_amount'
    },
    user_id: {
        show: false,
        title: i18n.t('message.owner'),
        sortable: true,
        column: 'user_id'
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