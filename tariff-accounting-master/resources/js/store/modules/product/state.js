import { columns } from './properties/columns'
import { filter } from './properties/filter'
import { sort } from './../properties/sort'
import { pagination } from './../properties/pagination'
import { model } from "./properties/model";
import { rules } from "./properties/rules";
import { product_material } from "./properties/product_material";
import { semi_product } from "./properties/semi_product";

export const state = {
    list: [],
    inventory: [],
    semi_product_list: [],
    model: JSON.parse(JSON.stringify(model)),
    columns: columns,
    filter: filter,
    pagination: JSON.parse(JSON.stringify(pagination)),
    sort: JSON.parse(JSON.stringify(sort)),
    rules: rules,
    product_material: product_material,
    product_materials: [],
    semi_product: semi_product,
    semi_products: [],
};