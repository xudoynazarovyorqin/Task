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
        column: 'number'
    },
    products: {
        show: true,
        title: i18n.t('message.products'),
        sortable: false,
        column: 'products'
    },
    owner: {
        show: false,
        title: i18n.t('message.owner'),
        sortable: true,
        column: 'owner'
    },
    begin_date: {
        show: true,
        title: i18n.t('message.Begin date'),
        sortable: true,
        column: 'begin_date'
    },
    end_date: {
        show: true,
        title: i18n.t('message.End date'),
        sortable: true,
        column: 'end_date'
    },
    state_id: {
        show: true,
        title: i18n.t('message.status'),
        sortable: true,
        column: 'state_id'
    },
    priority_id: {
        show: true,
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
    level_id: {
        show: false,
        title: i18n.t('message.level'),
        sortable: true,
        column: 'level_id',
        editable: false,
        changeable: false,
    },
    saleable_type: {
        show: false,
        title: i18n.t('message.Production why'),
        sortable: true,
        column: 'saleable_type'
    },
    saleable_id: {
        show: false,
        title: i18n.t('message.Production why') + i18n.t('message.n'),
        sortable: true,
        column: 'saleable_id'
    },
    manufactured: {
        show: true,
        title: i18n.t('message.ready'),
        sortable: false,
        column: 'manufactured'
    },
    settings: {
        show: true,
        title: i18n.t('message.settings'),
        sortable: false,
        column: 'settings'
    }
};