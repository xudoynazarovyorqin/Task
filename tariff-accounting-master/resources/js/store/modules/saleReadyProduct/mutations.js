import { model } from "./properties/model";
import { filter } from "./properties/filter";
import { sort } from "../properties/sort";
import { pagination } from "../properties/pagination";
import { i18n } from '@/utils/modules/i18n';

export const mutations = {
    SET_LIST: (state, sales) => {
        if (_.isArray(sales)) {
            state.list = [];
            sales.forEach(element => {
                element.not_paid = element.total_price - element.paid_price;
                element.contract_client = (element.contract_client ? '№ ' + element.contract_client.number + ' ' + i18n.t('message.from') + ' ' + element.contract_client.begin_date : '');
                state.list.push(element);
            });
        }
    },
    SET_INVENTORY: (state, sales) => (state.inventory = sales),
    SET_FILTER: (state, filter) => (state.filter = filter),
    CLEAR_FILTER: (state) => (state.filter = JSON.parse(JSON.stringify(filter))),
    SET_PAGINATION: (state, pagination) => (state.pagination = pagination),
    SET_SORT: (state, sort) => (state.sort = sort),
    SET_LAST_ID: (state, lastId) => {
        state.last_id = lastId;
    },
    INCREMENT_LAST_ID: state => ++state.last_id,
    UPDATE_COLUMN: (state, obj) => {
        state.columns[obj.key].show = obj.value
    },
    UPDATE_SORT: (state, sort) => {
        state.sort[sort.column] = sort.order
    },
    UPDATE_PAGINATION: (state, pagination) => {
        state.pagination[pagination.key] = pagination.value
    },

    SET_MODEL: (state, model) => {
        state.model = model;
        /**
         * Set sale products
         * @type {Array}
         */
        state.items = [];
        if (model.items && _.isArray(model.items)) {
            model.items.forEach(item => {
                state.items.push({
                    id: item.id,
                    product: item.product,
                    quantity: parseFloat(item.quantity),
                    selling_price: parseFloat(item.selling_price),
                    rate: item.currency ? (item.currency.reverse ? _.round(1 / item.rate, 8) : item.rate) : 1,
                    currency: item.currency,
                });
            });
        };
    },
    EMPTY_MODEL: (state) => {
        state.model = JSON.parse(JSON.stringify(model));
        state.items = [];
    },
    REFRESH: (state) => {
        state.filter = JSON.parse(JSON.stringify(filter));
        state.sort = JSON.parse(JSON.stringify(sort));
        state.pagination = JSON.parse(JSON.stringify(pagination));
    }
};