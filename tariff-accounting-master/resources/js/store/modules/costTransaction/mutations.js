import { sort } from "../properties/sort";
import { filter } from "./properties/filter";
import { pagination } from "../properties/pagination";
import { i18n } from '@/utils/modules/i18n';

export const mutations = {
    SET_LIST: (state, costTransactions) =>{
        state.list = [];
        costTransactions.forEach(element => {
            element.incoming = element.debit == 1 ? true : false;
            element.debit = element.debit == 1 ? i18n.t('message.Incoming payment') : i18n.t('message.Outgoing payment');
            state.list.push(element);
        });
    },
    SET_FILTER: (state, filter) => (state.filter = filter),
    SET_PAGINATION: (state, pagination) => (state.pagination = pagination),
    SET_SORT: (state, sort) => (state.sort = sort),
    UPDATE_COLUMN: (state, obj) => {
        state.columns[obj.key].show = obj.value
    },
    UPDATE_SORT: (state, sort) => {
        state.sort[sort.column] = sort.order
    },
    UPDATE_PAGINATION: (state, pagination) => {
        state.pagination[pagination.key] = pagination.value
    },
    REFRESH: (state) => {
        state.filter = JSON.parse(JSON.stringify(filter));
        state.sort = JSON.parse(JSON.stringify(sort));
        state.pagination = JSON.parse(JSON.stringify(pagination));
    }
};