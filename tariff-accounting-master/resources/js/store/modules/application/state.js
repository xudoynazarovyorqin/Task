import { columns } from './properties/columns'
import { filter } from './properties/filter'
import { model } from './properties/model'
import { sort } from './../properties/sort'
import { pagination } from './../properties/pagination'
import { rules } from "./properties/rules";

export const state = {
    /*
     * Default data
     */
    columns: columns,
    rules: rules,
    filter: JSON.parse(JSON.stringify(filter)),
    pagination: JSON.parse(JSON.stringify(pagination)),
    sort: JSON.parse(JSON.stringify(sort)),

    /*
     * Dynamic data
     */
    list: [],
    lastId: null,
    total_amount: 0,
    total_paid: 0,
    application_counts: {},
    model: JSON.parse(JSON.stringify(model)),
    application_services: [],
    audits: [],
};
