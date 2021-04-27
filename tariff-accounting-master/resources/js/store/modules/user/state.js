import { columns } from './properties/columns'
import { filter } from './properties/filter'
import { pagination } from "../properties/pagination";
import { sort } from "../properties/sort";
import { rules } from "./properties/rules";
import { model } from "./properties/model";
import { i18n } from '@/utils/modules/i18n';

export const state = {
    list: [],
    inventory: [],
    model: JSON.parse(JSON.stringify(model)),
    columns: columns,
    filter: filter,
    pagination: JSON.parse(JSON.stringify(pagination)),
    sort: JSON.parse(JSON.stringify(sort)),
    statues: {
        active: i18n.t('message.active'),
        deactive: i18n.t('message.deactive'),
    },
    rules: rules,
    permissions: []
};