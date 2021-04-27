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
    code: {
        show: true,
        title: i18n.t('message.code'),
        sortable: true,
        column: 'code'
    },
    vendor_code: {
        show: false,
        title: i18n.t('message.vendor_code'),
        sortable: true,
        column: 'vendor_code'
    },
    measurement_id: {
        show: true,
        title: i18n.t('message.measurement'),
        sortable: true,
        column: 'measurement_id'
    },
    remainder: {
        show: true,
        title: i18n.t('message.available'),
        sortable: true,
        column: 'remainder'
    },
    booked: {
        show: true,
        title: i18n.t('message.Booked'),
        sortable: true,
        column: 'remainder'
    },
    cost_price: {
        show: true,
        title: i18n.t('message.price'),
        sortable: false,
        column: 'cost_price'
    },
    total_cost_price: {
        show: true,
        title: i18n.t('message.The cost amount'),
        sortable: false,
        column: 'total_cost_price'
    },
    selling_price: {
        show: true,
        title: i18n.t('message.Sale price'),
        sortable: false,
        column: 'selling_price'
    },
    total_selling_price: {
        show: true,
        title: i18n.t('message.The amount of the sale'),
        sortable: false,
        column: 'total_selling_price'
    },
    settings: {
        show: true,
        title: i18n.t('message.settings'),
        sortable: false,
        column: 'settings',
        changeable: false,
    },
};