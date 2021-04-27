import { columns } from './properties/columns'
import { filter } from './properties/filter'
import { model } from './properties/model'
import { sort } from './../properties/sort'
import { pagination } from './../properties/pagination'
import { rules } from "./properties/rules";
import { i18n } from '@/utils/modules/i18n';

export const state = {
    list: [],
    shipment_products: [],
    model: JSON.parse(JSON.stringify(model)),
    rules: rules,
    columns: columns,
    filter: JSON.parse(JSON.stringify(filter)),
    pagination: JSON.parse(JSON.stringify(pagination)),
    sort: JSON.parse(JSON.stringify(sort)),
    selectedRow: null,
    shipmentable_types: {
        assemblies: i18n.t('message.assembly'),
        orders: i18n.t('message.order'),
        sale_ready_products: i18n.t('message.sale_ready_product'),
    }
};