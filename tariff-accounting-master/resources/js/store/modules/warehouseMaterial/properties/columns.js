import { i18n } from '@/utils/modules/i18n';

export const columns = {
    id: {
        show: true,
        title: i18n.t('message.n'),
        sortable: true,
        column: 'id',
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
    sku: {
        show: false,
        title: i18n.t('message.vendor_code'),
        sortable: true,
        column: 'sku'
    },
    critical_weight: {
        show: false,
        title: i18n.t('message.Critical stake'),
        sortable: true,
        column: 'critical_weight'
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
    buy_price: {
        show: true,
        title: i18n.t('message.buy_price'),
        sortable: false,
        column: 'buy_price'
    },
    total_buy_price: {
        show: true,
        title: i18n.t('message.The cost amount'),
        sortable: false,
        column: 'total_buy_price'
    },
    price: {
        show: true,
        title: i18n.t('message.Sale price'),
        sortable: false,
        column: 'price'
    },
    total_price: {
        show: true,
        title: i18n.t('message.The amount of the sale'),
        sortable: false,
        column: 'total_price'
    },
    settings: {
        show: true,
        title: i18n.t('message.settings'),
        sortable: false,
        column: 'settings'
    },
};