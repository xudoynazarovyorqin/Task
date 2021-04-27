import { columns } from './properties/columns'
import { filter } from './properties/filter'
import { model } from "./properties/model";
import { rules } from "./properties/rules";
import { pagination } from "../properties/pagination";
import { sort } from "../properties/sort";

export const state = {
    list: [],
    inventory: [],
    model: JSON.parse(JSON.stringify(model)),
    columns: columns,
    filter: filter,
    pagination: JSON.parse(JSON.stringify(pagination)),
    sort: JSON.parse(JSON.stringify(sort)),
    rules: rules,
    permissions: []
};