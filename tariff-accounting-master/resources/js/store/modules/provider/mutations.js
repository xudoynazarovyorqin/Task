import { model } from "./properties/model";
import { filter } from "./properties/filter";
import { sort } from "../properties/sort";
import { pagination } from "../properties/pagination";

export const mutations = {
    SET_LIST: (state, providers) => {
        state.list = [];
        providers.forEach(item => {
            item.total_buy_not_paid = item.total_buy - item.total_buy_paid;
            state.list.push(item);
        });
    },
    SET_INVENTORY: (state, providers) => (state.inventory = providers),
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
        if (model.provider_contact_persons) {
            state.provider_contact_persons = model.provider_contact_persons;
        }

        /**
         * set provider_checking_accounts
         */
        if (model.provider_checking_accounts) {
            state.provider_checking_accounts = model.provider_checking_accounts;
        }
    },
    EMPTY_MODEL: (state) => {
        state.model = JSON.parse(JSON.stringify(model));
        state.provider_contact_persons = [];
        state.provider_checking_accounts = []
    },
    REFRESH: (state) => {
        state.filter = JSON.parse(JSON.stringify(filter));
        state.sort = JSON.parse(JSON.stringify(sort));
        state.pagination = JSON.parse(JSON.stringify(pagination));
    }
};