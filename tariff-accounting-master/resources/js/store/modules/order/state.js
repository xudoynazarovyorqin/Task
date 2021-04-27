import { columns } from './properties/columns'
import { filter } from './properties/filter'
import { model } from './properties/model'
import { sort } from './../properties/sort'
import { pagination } from './../properties/pagination'
import { rules } from "./properties/rules";

export const state = {
    /*
     * Default data
     */
    columns: columns,
    rules: rules,
    filter: JSON.parse(JSON.stringify(filter)),
    pagination: JSON.parse(JSON.stringify(pagination)),
    sort: JSON.parse(JSON.stringify(sort)),

    /*
     * Dynamic data
     */
    list: [],
    inventory: [],
    lastId: null,
    model: JSON.parse(JSON.stringify(model)),
    order_products: [],
    employeeGroups: [],
    order_costs: [],
    additional_materials: [],
    created_audit: {},
    comments: []
};