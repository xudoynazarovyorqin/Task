import { columns } from './properties/columns'
import { filter } from './properties/filter'
import { sort } from '../properties/sort'
import { pagination } from '../properties/pagination'
import { model } from "./properties/model";
import { rules } from "./properties/rules";

export const state = {
    list: [],
    shipment_products: [],
    old_defect_products: [],
    model: JSON.parse(JSON.stringify(model)),
    columns: columns,
    filter: filter,
    pagination: JSON.parse(JSON.stringify(pagination)),
    sort: JSON.parse(JSON.stringify(sort)),
    rules: rules,
    history: []
};