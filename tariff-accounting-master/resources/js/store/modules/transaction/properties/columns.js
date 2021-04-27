import { i18n } from '@/utils/modules/i18n';

export const columns = {
    id: {
        show: true,
        title: i18n.t('message.n'),
        sortable: true,
        column: 'id'
    },
    payment_system: {
        show: true,
        title: i18n.t('message.payment_system'),
        sortable: true,
        column: 'payment_system'
    },
    system_transaction_id: {
        show: false,
        title: i18n.t('message.system_transaction_id'),
        sortable: true,
        column: 'system_transaction_id'
    },
    amount: {
        show: true,
        title: i18n.t('message.Amount'),
        sortable: true,
        column: 'amount'
    },
    state: {
        show: true,
        title: i18n.t('message.state'),
        sortable: true,
        column: 'state'
    },
    comment: {
        show: false,
        title: i18n.t('message.comment'),
        sortable: true,
        column: 'comment'
    },
    transactionable_type: {
        show: true,
        title: i18n.t('message.type_document'),
        sortable: true,
        column: 'transactionable_type'
    },
    transactionable_id: {
        show: true,
        title: i18n.t('message.Document number'),
        sortable: true,
        column: 'transactionable_id'
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
    return: {
        show: true,
        title: i18n.t('message.Return'),
        sortable: false,
        column: 'return'
    },
    settings: {
        show: false,
        title: i18n.t('message.settings'),
        sortable: false,
        column: 'settings',
        changeable: false,
    }
};
