import { columns } from './properties/columns'
import { filter } from './properties/filter'
import { model } from './properties/model'
import { sort } from '../properties/sort'
import { pagination } from '../properties/pagination'
import { rules } from "./properties/rules";

export const state = {
    list: [],
    inventory: [],
    last_id: 0,
    model: JSON.parse(JSON.stringify(model)),
    rules: rules,
    items: [],
    columns: columns,
    filter: JSON.parse(JSON.stringify(filter)),
    pagination: JSON.parse(JSON.stringify(pagination)),
    sort: JSON.parse(JSON.stringify(sort)),
};