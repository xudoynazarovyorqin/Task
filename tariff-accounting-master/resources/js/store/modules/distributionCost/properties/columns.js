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
    type: {
        show: true,
        title: i18n.t('message.type'),
        sortable: true,
        column: 'type'
    },
    from_date: {
        show: true,
        title: i18n.t('message.from_date'),
        sortable: true,
        column: 'from_date'
    },
    to_date: {
        show: true,
        title: i18n.t('message.to_date'),
        sortable: true,
        column: 'to_date'
    },    
    user_id: {
        show: false,
        title: i18n.t('message.user'),
        sortable: true,
        column: 'user_id'
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