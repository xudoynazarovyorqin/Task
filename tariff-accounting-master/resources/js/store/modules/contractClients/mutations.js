import { sort } from "../properties/sort";
import { filter } from "./properties/filter";
import { pagination } from "../properties/pagination";
import { model } from "./properties/model";


export const mutations = {
    SET_LIST: (state, list) => (state.list = list),
    SET_INVENTORY: (state, list) => (state.inventory = list),
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
    SET_STATUES: (state, statuses) => {
        state.statuses = statuses
    },
    SET_MODEL: (state, model) => {
        state.model = model;

        /**
         * set contract_client_suspenses
         */
        if (model.suspenses) {
            state.contract_client_suspenses = model.suspenses;
        }
    },
    EMPTY_MODEL: (state) => {
        state.model = JSON.parse(JSON.stringify(model));
        state.contract_products = []
        state.contract_client_suspenses = []
    },
    REFRESH: (state) => {
        state.filter = JSON.parse(JSON.stringify(filter));
        state.sort = JSON.parse(JSON.stringify(sort));
        state.pagination = JSON.parse(JSON.stringify(pagination));
    }
};