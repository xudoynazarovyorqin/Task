import { columns } from './properties/columns'
import { buy_model } from './properties/buy_model'
import { rules } from "./properties/rules";
import { filter } from './properties/filter'
import { sort } from '../properties/sort'
import { pagination } from '../properties/pagination'

export const state = {
    list: [],
    buy_model: JSON.parse(JSON.stringify(buy_model)),
    rules: rules,
    buy_products: [],
    columns: columns,
    filter: JSON.parse(JSON.stringify(filter)),
    pagination: JSON.parse(JSON.stringify(pagination)),
    sort: JSON.parse(JSON.stringify(sort))
};