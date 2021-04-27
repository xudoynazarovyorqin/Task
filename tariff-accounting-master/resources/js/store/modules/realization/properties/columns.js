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
    realizationable_type: {
        show: true,
        title: i18n.t('message.type_document'),
        sortable: false,
        column: 'realizationable_type'
    },
    realizationable_id: {
        show: false,
        title: i18n.t('message.Document number'),
        sortable: true,
        column: 'realizationable_id'
    },
    user_id: {
        show: true,
        title: i18n.t('message.To whom'),
        sortable: true,
        column: 'user_id'
    },
    materials: {
        show: true,
        title: i18n.t('message.materials'),
        sortable: false,
        column: 'materials'
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