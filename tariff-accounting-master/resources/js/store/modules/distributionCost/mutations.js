import { sort } from "../properties/sort";
import { filter } from "./properties/filter";
import { pagination } from "../properties/pagination";
import { model } from "./properties/model";
import { i18n } from '@/utils/modules/i18n';

export const mutations = {
    SET_LIST: (state, distribution_costs) => (state.list = distribution_costs),
    SET_FILTER: (state, filter) => (state.filter = filter),
    SET_PAGINATION: (state, pagination) => (state.pagination = pagination),
    SET_SORT: (state, sort) => (state.sort = sort),
    SET_LAST_ID: (state, lastId) => (state.lastId = lastId),
    INCREMENT_LAST_ID: (state) => (++state.lastId),
    SET_MODEL: (state, model) => {
        state.model = model;
        /**
         * Set items
         */
        state.items = [];
        if (model.additional_prices) {
            model.additional_prices.forEach(element => {
                state.items.push(element);
            });
        }
        
        /**
         * Set transactions
         */ 
        state.transactions = [];

        if (model.distribution_transactions) {
            model.distribution_transactions.forEach(element => {
                state.transactions.push(element);
            });
        }

        /**
         * Set old_transactions
         */ 
        state.old_transactions = [];

        if (model.distribution_transactions) {
            model.distribution_transactions.forEach(element => {
                state.old_transactions.push(element);
            });
        }
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
        state.items = [];
        state.transactions = [];
    },
    REFRESH: (state) => {
        state.filter = JSON.parse(JSON.stringify(filter));
        state.sort = JSON.parse(JSON.stringify(sort));
        state.pagination = JSON.parse(JSON.stringify(pagination));
    },
    SET_ITEMS: (state, items) => {
        state.items = items;
    },
    SET_TRANSACTIONS: (state, transactions) => {
        state.transactions = transactions;
    },
    SET_OLD_TRANSACTIONS: (state, old_transactions) => {
        state.old_transactions = old_transactions;
    }
};