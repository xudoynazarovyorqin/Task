import { model } from "./properties/model";
import { filter } from "./properties/filter";
import { sort } from "../properties/sort";
import { pagination } from "../properties/pagination";

export const mutations = {
    SET_LIST: (state, scores) => (state.list = scores),
    SET_INVENTORY: (state, scores) => (state.inventory = scores),
    SET_FILTER: (state, filter) => (state.filter = filter),
    SET_PAGINATION: (state, pagination) => (state.pagination = pagination),
    SET_SORT: (state, sort) => (state.sort = sort),
    SET_LAST_ID: (state, lastId) => (state.lastId = lastId),
    INCREMENT_LAST_ID: (state) => (++state.lastId),
    UPDATE_COLUMN: (state, obj) => {
        state.columns[obj.key].show = obj.value
    },
    UPDATE_SORT: (state, sort) => {
        state.sort[sort.column] = sort.order;
    },
    UPDATE_PAGINATION: (state, pagination) => {
        state.pagination[pagination.key] = pagination.value
    },
    SET_MODEL: (state, model) => {
        state.model = model;
        state.realization_materials = [];
        if (model.realization_materials && _.isArray(model.realization_materials)) {
            state.realization_materials = model.realization_materials;
        }
    },
    EMPTY_MODEL: (state) => {
        state.model = JSON.parse(JSON.stringify(model));
        state.realization_materials = []
    },
    REFRESH: (state) => {
        state.filter = JSON.parse(JSON.stringify(filter));
        state.sort = JSON.parse(JSON.stringify(sort));
        state.pagination = JSON.parse(JSON.stringify(pagination));
    },
    SET_SELECTED_ROW: (state, row) => {
        state.selectedRow = row
    }
};