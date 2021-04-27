import { columns } from './properties/columns'
import { filter } from './properties/filter'
import { model } from './properties/model'
import { sort } from '../properties/sort'
import { pagination } from '../properties/pagination'
import { warehouse_material } from "./properties/warehouse_material";

export const state = {
    list: [],
    comingMaterials: [],
    warehouse_material: warehouse_material,
    model: JSON.parse(JSON.stringify(model)),
    columns: columns,
    filter: JSON.parse(JSON.stringify(filter)),
    pagination: JSON.parse(JSON.stringify(pagination)),
    sort: JSON.parse(JSON.stringify(sort))
};