import { columns } from './properties/columns'
import { filter } from './properties/filter'
import { sort } from './../properties/sort'
import { pagination } from './../properties/pagination'
import { model } from "./properties/model";
import { rules } from "./properties/rules";
import { i18n } from '@/utils/modules/i18n';

export const state = {
    list: [],
    lastId: null,
    model: JSON.parse(JSON.stringify(model)),
    rules: rules,
    columns: columns,
    filter: filter,
    selectedRow: null,
    pagination: JSON.parse(JSON.stringify(pagination)),
    sort: JSON.parse(JSON.stringify(sort)),
    realization_materials: [],
    realization_types: {
        sales: i18n.t('message.sales'),
        assemblies: i18n.t('message.assemblies')
    }
};