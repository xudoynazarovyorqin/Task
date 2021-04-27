import { model } from "./properties/model";
import { filter } from "./properties/filter";
import { sort } from "../properties/sort";
import { pagination } from "../properties/pagination";

export const mutations = {
    SET_LIST: (state, clients) => {
        state.list = [];
        clients.forEach(item => {
            item.total_sale_sum_not_paid = item.total_sale_sum - item.total_sale_sum_paid;
            state.list.push(item);
        });
    },
    SET_INVENTORY: (state, clients) => (state.inventory = clients),
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
    SET_TYPES: (state, types) => {
        state.types = types
    },
    SET_MODEL: (state, model) => {
        state.model = model;
        /**
         * set client_contact_persons
         */
        if (model.client_contact_persons) {
            state.client_contact_persons = model.client_contact_persons;
        }

        /**
         * set client_checking_accounts
         */
        if (model.client_checking_accounts) {
            state.client_checking_accounts = model.client_checking_accounts;
        }
    },
    EMPTY_MODEL: (state) => {
        state.model = JSON.parse(JSON.stringify(model));
        state.client_contact_persons = [];
        state.client_checking_accounts = [];
        state.sales = [];
        state.saleReadyProducts = [];
    },
    REFRESH: (state) => {
        state.filter = JSON.parse(JSON.stringify(filter));
        state.sort = JSON.parse(JSON.stringify(sort));
        state.pagination = JSON.parse(JSON.stringify(pagination));
    }
};