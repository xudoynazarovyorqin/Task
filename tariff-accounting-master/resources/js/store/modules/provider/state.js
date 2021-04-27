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
    columns: columns,
    rules: rules,
    filter: JSON.parse(JSON.stringify(filter)),
    pagination: JSON.parse(JSON.stringify(pagination)),
    sort: JSON.parse(JSON.stringify(sort)),
    types: [],
    provider_checking_accounts: [],
    provider_contact_persons: []
};