import { columns } from './properties/columns'
import { filter } from './properties/filter'
import { sort } from './../properties/sort'
import { pagination } from './../properties/pagination'
import { i18n } from '@/utils/modules/i18n';

export const state = {
    list: [],
    columns: columns,
    filter: JSON.parse(JSON.stringify(filter)),
    pagination: JSON.parse(JSON.stringify(pagination)),
    sort: JSON.parse(JSON.stringify(sort)),
    events: {
        created: i18n.t('message.Creation'), 
        updated: i18n.t('message.Update'), 
        deleted: i18n.t('message.Removal'), 
    }
};