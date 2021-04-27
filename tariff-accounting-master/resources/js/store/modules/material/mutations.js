import { sort } from "../properties/sort";
import { filter } from "./properties/filter";
import { pagination } from "../properties/pagination";
import { model } from "./properties/model";

export const mutations = {
    SET_LIST: (state, materials) => {
        state.list = [];
        materials.forEach(element => {
            element.is_active = (element.is_active) ? 'Да' : 'Нет';
            element.is_reworking = (element.is_reworking) ? 'Да' : 'Нет';
            element.measurement_changeable = (element.measurement_changeable) ? 'Да' : 'Нет';
            state.list.push(element)
        });
    },
    SET_INVENTORY: (state, materials) => { state.inventory = materials },
    SET_REWORKING_MATERIALS: (state, materials) => (state.reworking_materials = materials),
    SET_TYPES: (state, types) => (state.types = types),
    SET_FILTER: (state, filter) => (state.filter = filter),
    SET_MODEL: (state, model) => {
        state.model = model;
    },
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
    EMPTY_MODEL: (state) => {
        state.model = JSON.parse(JSON.stringify(model));
    },
    REFRESH: (state) => {
        state.filter = JSON.parse(JSON.stringify(filter));
        state.sort = JSON.parse(JSON.stringify(sort));
        state.pagination = JSON.parse(JSON.stringify(pagination));
    }
};