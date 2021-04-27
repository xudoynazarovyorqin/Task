import { sort } from "../properties/sort";
import { filter } from "./properties/filter";
import { pagination } from "../properties/pagination";
import { model } from "./properties/model";

export const mutations = {
    SET_LIST: (state, defect_products) => (state.list = defect_products),
    SET_FILTER: (state, filter) => (state.filter = filter),
    SET_PAGINATION: (state, pagination) => (state.pagination = pagination),
    SET_SORT: (state, sort) => (state.sort = sort),
    SET_MODEL: (state, model) => {
        state.model.id = model.id;
        state.model.sale_id = (model.sale) ? model.sale.id : '';
        state.model.quantity = model.quantity;
        state.model.date = model.date;
        state.model.product = model.product
        state.model.product_id = (model.product) ? model.product.id : ''
        state.model.created_at = model.created_at;
        state.model.updated_at = model.updated_at;
    },
    SET_SHIPMENT_PRODUCTS: (state, model) => {
        /**
         * set main data
         */

        // state.model.defectable_type = 'shipment_products';
        state.model.shipment_id = model.id;
        state.model.client_name = (model.client) ? model.client.name : '';

        /**
         * set shipment_products
         * @type {Array}
         */
        state.shipment_products = [];
        if (model.shipment_products) {
            for (let key in model.shipment_products) {
                if (model.shipment_products.hasOwnProperty(key)) {
                    let element = model.shipment_products[key];

                    state.shipment_products.push({
                        shipment_product_id: element.id,
                        product_id: element.product_id,
                        product_name: (element.product) ? element.product.name : '',
                        measurement: (element.product && element.product.measurement) ? element.product.measurement.name : '',
                        shimpent_quantity: parseFloat(element.quantity),
                        max_quantity: parseFloat(element.quantity) - parseFloat(element.sum_defect_products),
                    })
                }
            }
        }
    },

    SET_OLD_DEFECT_PRODUCTS: (state, old_defect_products) => (state.old_defect_products = old_defect_products),

    SET_HISTORY: (state, history) => (state.history = history.data),
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