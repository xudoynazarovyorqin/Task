import { filter } from "./properties/filter";
import { sort } from "../properties/sort";
import { pagination } from "../properties/pagination";

export const mutations = {
    SET_LIST: (state, materials) => {
        state.list = [];
        materials.forEach(element => {
            element.buy_price = element.total_buy_price / ((element.remainder) ? element.remainder : 1);
            element.price = element.total_price / ((element.remainder) ? element.remainder : 1);
            state.list.push(element);
        });
    },
    SET_COMING_MATERIALS: (state, warehouse_materials) => {
        state.comingMaterials = [];
        warehouse_materials.forEach(element => {
            element.currency = element.currency;
            element.rate = element.currency ? (element.currency.reverse ? _.round(1 / element.rate, 8) : element.rate) : 1;
            element.is_reworked = (element.is_reworked) ? 'Переработано' : 'Новое';
            state.comingMaterials.push(element)
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