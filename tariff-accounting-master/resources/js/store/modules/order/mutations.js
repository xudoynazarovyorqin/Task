import { model } from "./properties/model";
import { filter } from "./properties/filter";
import { sort } from "../properties/sort";
import { pagination } from "../properties/pagination";
import { i18n } from '@/utils/modules/i18n';

export const mutations = {
    SET_LIST: (state, orders) => {
        state.list = [];
        for (const key in orders) {
            if (orders.hasOwnProperty(key)) {
                const element = orders[key];
                element.not_paid = element.amount - element.paid;
                element.production_type = (element.production_type == 'production') ? i18n.t('message.sale') : i18n.t('message.assembly');
                element.client_id = (element.client) ? (element.client.name) : '';
                element.contract_client_id = (element.contract_client) ? ('№' + element.contract_client.number + ' ' + i18n.t('message.assembly') + ' ' + element.contract_client.begin_date) : '';
                state.list.push(element)
            }
        }
    },
    SET_INVENTORY: (state, orders) => (state.inventory = orders),
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
         * Set order data
         */
        if (payload.order) {
            const { order } = payload;
            state.model = order;
            /**
             *   Set order products
             */
            state.order_products = [];
            if (order.order_products) {
                const { order_products } = order;
                order_products.forEach(item => {
                    state.order_products.push({
                        id: item.id,
                        product: item.product,
                        price: parseFloat(item.price),
                        quantity: parseFloat(item.quantity),
                        ready: parseFloat(item.ready),
                        rate: item.currency ? (item.currency.reverse ? _.round(1 / item.rate, 8) : item.rate) : 1,
                        currency: item.currency,
                    })
                });
            }

            /**
             *   Set order costs
             */
            state.order_costs = [];
            if (order.order_costs) {
                const { order_costs } = order;
                order_costs.forEach(item => {
                    state.order_costs.push({
                        id: item.id,
                        cost: item.cost,
                        amount: parseFloat(item.amount),
                        rate: item.currency ? (item.currency.reverse ? _.round(1 / item.rate, 8) : item.rate) : 1,
                        currency: item.currency,
                    })
                });
            }

            state.created_audit = {};
            if (order.created_audit) {
                const { created_audit } = order;
                state.created_audit = created_audit;
            }
        }
        /*
         * Set order additional materials
         */
        state.additional_materials = [];
        if (payload.additional_materials) {
            const { additional_materials } = payload;
            additional_materials.forEach(item => {
                state.additional_materials.push({
                    id: item.id,
                    material: item.material,
                    quantity: parseFloat(item.quantity)
                })
            });
        }

        state.employeeGroups = [];
        for (const key in payload.employeeGroups) {
            if (payload.employeeGroups.hasOwnProperty(key)) {
                const element = payload.employeeGroups[key];
                state.employeeGroups.push(element)
            }
        }
    },
    SET_COMMENTS: (state, payload) => {
        /**
         * comments
         * @type {Array}
         */
        state.comments = [];
        if (payload.comments) {
            if (payload.comments) {
                for (let key in payload.comments) {
                    if (payload.comments.hasOwnProperty(key)) {
                        let element = payload.comments[key];
                        state.comments.push(element)
                    }
                }
            }
        }
    },
    empty: (state) => {
        state.model = JSON.parse(JSON.stringify(model));
        state.order_products = [];
        state.order_costs = [];
        state.additional_materials = [];
        state.employeeGroups = [];
    },
    REFRESH: (state) => {
        state.filter = JSON.parse(JSON.stringify(filter));
        state.sort = JSON.parse(JSON.stringify(sort));
        state.pagination = JSON.parse(JSON.stringify(pagination));
    }
};