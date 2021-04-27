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
        title: i18n.t('message.client_name'),
        sortable: true,
        column: 'name'
    },
    phone: {
        show: true,
        title: i18n.t('message.phone'),
        sortable: true,
        column: 'phone'
    },
    email: {
        show: true,
        title: i18n.t('message.email'),
        sortable: true,
        column: 'email'
    },
    object_name: {
        show: false,
        title: i18n.t('message.Object name'),
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
    actual_address: {
        show: false,
        title: i18n.t('message.address'),
        sortable: true,
        column: 'actual_address',
        changeable: false
    },
    type_id: {
        show: false,
        title: i18n.t('message.Type of client'),
        sortable: true,
        column: 'type_id',
        changeable: false
    },
    comment: {
        show: false,
        title: i18n.t('message.comment'),
        sortable: true,
        column: 'comment'
    },
    inn: {
        show: false,
        title: i18n.t('message.inn'),
        sortable: true,
        column: 'inn',
        changeable: false
    },
    mfo: {
        show: false,
        title: i18n.t('message.mfo'),
        sortable: true,
        column: 'mfo',
        changeable: false
    },
    okonx: {
        show: false,
        title: i18n.t('message.okonx'),
        sortable: true,
        column: 'okonx',
        changeable: false
    },
    oked: {
        show: false,
        title: i18n.t('message.oked'),
        sortable: true,
        column: 'oked',
        changeable: false
    },
    rkp_nds: {
        show: false,
        title: i18n.t('message.Rkp nds'),
        sortable: true,
        column: 'rkp_nds',
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
    }
};
