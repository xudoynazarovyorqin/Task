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
    model: JSON.parse(JSON.stringify(model)),

    list: [],
    lastId: null,
    sale_products: [],
    employeeGroups: [],
    additional_materials: [],

    histories: [],
    created_info: {},
    comments: [],
    defect_products: [],
    manufactured_products: [],
};