import { columns } from './properties/columns'
import { filter } from './properties/filter'
import { model } from './properties/model'
import { sort } from '../properties/sort'
import { pagination } from '../properties/pagination'

export const state = {
    list: [],
    comingProducts: [],
    model: JSON.parse(JSON.stringify(model)),
    columns: columns,
    filter: JSON.parse(JSON.stringify(filter)),
    pagination: JSON.parse(JSON.stringify(pagination)),
    sort: JSON.parse(JSON.stringify(sort))
};