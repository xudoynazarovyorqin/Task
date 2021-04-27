import { sort } from "../properties/sort";
import { filter } from "./properties/filter";
import { pagination } from "../properties/pagination";
import { model } from "./properties/model";


export const mutations = {
    SET_LIST: (state, users) => {
        state.list = [];
        users.forEach(item => {
            item.status = state.statues[item.status];
            state.list.push(item);
        });
    },
    SET_INVENTORY: (state, users) => (state.inventory = users),
    SET_SORT: (state, sort) => (state.sort = sort),
    SET_FILTER: (state, filter) => (state.filter = filter),
    SET_PAGINATION: (state, pagination) => (state.pagination = pagination),
    UPDATE_PAGINATION: (state, pagination) => {
        state.pagination[pagination.key] = pagination.value
    },
    UPDATE_COLUMN: (state, obj) => {
        state.columns[obj.key].show = obj.value
    },
    UPDATE_SORT: (state, sort) => {
        state.sort[sort.column] = sort.order
    },
    SET_MODEL: (state, user) => {
        state.model = user;

        if (user.role) {
            if (user.role.permissions) {
                state.permissions = user.role.permissions
            }
        }

        /***
         * set user employee_groups
         */
        state.model.employee_groups = [];
        if (user.employee_groups) {
            if (user.employee_groups) {
                for (let key in user.employee_groups) {
                    if (user.employee_groups.hasOwnProperty(key)) {
                        state.model.employee_groups.push(user.employee_groups[key].id)
                    }
                }
            }
        }
    },
    EMPTY_MODEL: (state) => {
        state.model = JSON.parse(JSON.stringify(model));
        state.permissions = [];
    },
    REFRESH: (state) => {
        state.filter = JSON.parse(JSON.stringify(filter));
        state.sort = JSON.parse(JSON.stringify(sort));
        state.pagination = JSON.parse(JSON.stringify(pagination));
    }

};