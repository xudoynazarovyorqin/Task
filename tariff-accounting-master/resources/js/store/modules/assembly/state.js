import { columns } from './properties/columns'
import { filter } from './properties/filter'
import { model } from './properties/model'
import { sort } from './../properties/sort'
import { pagination } from './../properties/pagination'
import { rules } from "./properties/rules";

export const state = {
    rules: rules,
    columns: columns,
    filter: JSON.parse(JSON.stringify(filter)),
    pagination: JSON.parse(JSON.stringify(pagination)),
    sort: JSON.parse(JSON.stringify(sort)),

    /**
     * Dinamic
     */

    list: [],
    model: JSON.parse(JSON.stringify(model)),
    lastId: null,
    employeeGroups: [],
    assembly_items: [],
    additional_materials: [],
    manufactured_products: [],
    defect_products: [],
    comments: [],
    created_audit: {}
};