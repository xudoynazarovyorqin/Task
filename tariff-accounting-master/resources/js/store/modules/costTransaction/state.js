import { columns } from './properties/columns';
import { filter } from './properties/filter';
import { sort } from '../properties/sort';
import { pagination } from '../properties/pagination';

export const state = {
    list: [],
    columns: columns,
    filter: filter,
    pagination: JSON.parse(JSON.stringify(pagination)),
    sort: JSON.parse(JSON.stringify(sort)),
};