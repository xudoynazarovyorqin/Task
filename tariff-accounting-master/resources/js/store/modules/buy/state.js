import { columns } from './properties/columns'
import { filter } from './properties/filter'
import { model } from './properties/model'
import { sort } from './../properties/sort'
import { pagination } from './../properties/pagination'
import { rules } from "./properties/rules";

export const state = {
    list: [],
    inventory: [],
    lastId: null,
    statuses: [],
    model: JSON.parse(JSON.stringify(model)),
    rules: rules,
    buy_materials: [],
    warehouse_materials: [],
    columns: columns,
    filter: JSON.parse(JSON.stringify(filter)),
    pagination: JSON.parse(JSON.stringify(pagination)),
    sort: JSON.parse(JSON.stringify(sort))
};