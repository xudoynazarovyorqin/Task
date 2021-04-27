import { sort } from "../properties/sort";
import { filter } from "./properties/filter";
import { pagination } from "../properties/pagination";
import { model } from "./properties/model";
import { i18n } from '@/utils/modules/i18n';

export const mutations = {
    SET_LIST: (state, transactions) => ( state.list = transactions ),
    SET_AMOUNTS: (state, amounts) => ( state.transaction_amounts = amounts ),
    SET_FILTER: (state, filter) => (state.filter = filter),
    SET_PAGINATION: (state, pagination) => (state.pagination = pagination),
    SET_SORT: (state, sort) => (state.sort = sort),
    SET_LAST_ID: (state, lastId) => (state.lastId = lastId),
    INCREMENT_LAST_ID: (state) => (++state.lastId),
    SET_MODEL: (state, model) => {
        state.model = model;
        /**
         * Set payments
         */
        // state.payments = [];
        // if (model.payments) {
        //     model.payments.forEach(payment => {
        //         state.payments.push(payment);
        //     });
        // }

    },
    UPDATE_COLUMN: (state, obj) => {
        state.columns[obj.key].show = obj.value
    },
    UPDATE_SORT: (state, sort) => {
        state.sort[sort.column] = sort.order
    },
    UPDATE_PAGINATION: (state, pagination) => {
        state.pagination[pagination.key] = pagination.value
    },
    EMPTY_MODEL: (state) => {
        state.model = JSON.parse(JSON.stringify(model));
        state.relatedItems = [];
        state.payments = [];
    },
    REFRESH: (state) => {
        state.filter = JSON.parse(JSON.stringify(filter));
        state.sort = JSON.parse(JSON.stringify(sort));
        state.pagination = JSON.parse(JSON.stringify(pagination));
    },
    SET_RELATED_ITEMS: (state, items) => {
        state.relatedItems = items;
    }
};
