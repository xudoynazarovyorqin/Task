import { model } from "./properties/model";
import { filter } from "./properties/filter";
import { sort } from "../properties/sort";
import { pagination } from "../properties/pagination";
import { i18n } from '@/utils/modules/i18n';

export const mutations = {
    SET_LIST: (state, applications) => ( state.list = applications ),
    SET_FILTER: (state, filter) => (state.filter = filter),
    CLEAR_FILTER: (state) => (state.filter = JSON.parse(JSON.stringify(filter))),
    SET_PAGINATION: (state, pagination) => (state.pagination = pagination),
    SET_LAST_ID: (state, lastId) => (state.lastId = lastId),
    INCREMENT_LAST_ID: (state) => (++state.lastId),
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
    EDIT_MODEL: (state, payload) => {
        /**
         * Set application data
         */
        if (payload.application) {
            const { application } = payload;
            state.model = application;
            /**
             *   Set application services
             */
            state.application_services = [];
            if (application.application_services) {
                const { application_services } = application;
                state.application_services = application_services;
            }
        }
    },
    empty: (state) => {
        state.model = JSON.parse(JSON.stringify(model));
        state.application_services = [];
        state.audits = [];
    },
    REFRESH: (state) => {
        state.filter = JSON.parse(JSON.stringify(filter));
        state.sort = JSON.parse(JSON.stringify(sort));
        state.pagination = JSON.parse(JSON.stringify(pagination));
    }
};
