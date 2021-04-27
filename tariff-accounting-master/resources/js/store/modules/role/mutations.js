import { model } from "./properties/model";
import { filter } from "./properties/filter";
import { sort } from "../properties/sort";
import { pagination } from "../properties/pagination";

export const mutations = {
    SET_LIST: (state, roles) => (state.list = roles),
    SET_INVENTORY: (state, roles) => (state.inventory = roles),
    CLEAR_FILTER: (state) => (state.filter = JSON.parse(JSON.stringify(filter))),
    SET_SORT: (state, sort) => (state.sort = sort),
    SET_FILTER: (state, filter) => (state.filter = filter),
    SET_PAGINATION: (state, pagination) => (state.pagination = pagination),
    SET_MODEL: (state, model) => {
        state.model = model;

        /**
         * Set permissions
         * @type {Array}
         */
        state.permissions = [];
        if (model.permissions) {
            for (let key in model.permissions) {
                if (model.permissions.hasOwnProperty(key)) {
                    let element = model.permissions[key];
                    state.permissions.push(element)
                }
            }
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
        state.permissions = []
    },
    REFRESH: (state) => {
        state.filter = JSON.parse(JSON.stringify(filter));
        state.sort = JSON.parse(JSON.stringify(sort));
        state.pagination = JSON.parse(JSON.stringify(pagination));
    }
};