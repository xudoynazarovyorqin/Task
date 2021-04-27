import { columns } from './properties/columns'
import { filter } from './properties/filter'
import { sort } from './../properties/sort'
import { pagination } from './../properties/pagination'
import { model } from "./properties/model";
import { rules } from "./properties/rules";

export const state = {
    list: [],
    inventory: [],
    model: JSON.parse(JSON.stringify(model)),
    filter: JSON.parse(JSON.stringify(filter)),
    pagination: JSON.parse(JSON.stringify(pagination)),
    sort: JSON.parse(JSON.stringify(sort)),
    columns: columns,
    rules: rules
};