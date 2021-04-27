import { model } from "./properties/model";
import { filter } from "./properties/filter";
import { sort } from "../properties/sort";
import { pagination } from "../properties/pagination";
import { i18n } from '@/utils/modules/i18n';

export const mutations = {
    SET_LIST: (state, sales) => {
        state.list = []
        for (const key in sales) {
            if (sales.hasOwnProperty(key)) {
                const element = sales[key];
                element.owner = (element.owner == 'firm') ? i18n.t('message.firm') : i18n.t('message.firm');
                element.saleable_type = (element.saleable_type == 'orders') ? i18n.t('message.order') : (element.saleable_type == 'assemblies') ? i18n.t('message.assembly') : i18n.t('message.for_warehouse');
                state.list.push(element)
            }
        }
    },
    SET_FILTER: (state, filter) => (state.filter = filter),
    SET_SORT: (state, sort) => (state.sort = sort),
    CLEAR_FILTER: (state) => (state.filter = JSON.parse(JSON.stringify(filter))),
    UPDATE_COLUMN: (state, obj) => (state.columns[obj.key].show = obj.value),
    UPDATE_SORT: (state, sort) => (state.sort[sort.column] = sort.order),
    SET_LAST_ID: (state, lastId) => (state.lastId = lastId),
    INCREMENT_LAST_ID: (state) => (++state.lastId),
    UPDATE_PAGINATION: (state, pagination) => (state.pagination[pagination.key] = pagination.value),
    EDIT: (state, payload) => {
        if (payload.sale) {
            const { sale } = payload;
            state.model = sale;
            /**
             * Set sale products
             */
            state.sale_products = [];
            if (sale.sale_products) {
                const { sale_products } = sale;
                sale_products.forEach(sale_product => {
                    state.sale_products.push({
                        id: sale_product.id,
                        product: sale_product.product,
                        quantity: sale_product.quantity,
                        ready: sale_product.ready,
                        defect_count: sale_product.defects_count
                    });
                });
            }
            /**
             * Set additional materials
             */
            state.additional_materials = [];
            if (sale.additional_materials) {
                const { additional_materials } = sale;
                additional_materials.forEach(additional_material => {
                    state.additional_materials.push({
                        id: additional_material.id,
                        material: additional_material.material,
                        quantity: additional_material.quantity,
                    });
                });
            }
            /**
             * Set histories
             * @type {Array}
             */
            state.histories = [];
            if (sale.histories) {
                for (let key in sale.histories) {
                    if (sale.histories.hasOwnProperty(key)) {
                        let history = sale.histories[key];
                        state.histories.push(history)
                    }
                }
            }
            /**
             * Created info
             */
            state.created_info = {};
            if (sale.created_info) {
                state.created_info = sale.created_info
            }
        }

        state.employeeGroups = [];
        for (const key in payload.employeeGroups) {
            if (payload.employeeGroups.hasOwnProperty(key)) {
                const element = payload.employeeGroups[key];
                state.employeeGroups.push(element)
            }
        }
    },
    MANUFACTURED_PRODUCTS: (state, payload) => {
        /*
         * Set manufactured products
         */
        state.manufactured_products = [];
        if (payload.warehouse_products && payload.warehouse_products.data) {
            const warehouse_products = payload.warehouse_products.data;
            warehouse_products.forEach(element => {
                element.rate = element.currency ? (element.currency.reverse ? _.round(1 / element.rate, 8) : element.rate) : 1;
                element.currency = element.currency;
                state.manufactured_products.push(element)
            })
        }
    },
    SALE_PRODUCTS: (state, payload) => {
        /*
         *   Set sale products
         */
        state.sale_products = [];
        if (payload.sale_products) {
            const { sale_products } = payload;
            sale_products.forEach(element => {
                state.sale_products.push(element);
            })
        }
    },
    DEFECT_PRODUCTS: (state, payload) => {
        /*
         *   Set defect products
         */
        state.defect_products = [];
        if (payload.defect_products) {
            const { defect_products } = payload;
            defect_products.forEach(element => {
                state.defect_products.push(element);
            })
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
    EMPTY_MODEL: (state) => {
        state.model = JSON.parse(JSON.stringify(model));
        state.sale_products = [];
        state.histories = [];
        state.created_info = {};
        state.defect_products = []
        state.employeeGroups = [];
    },
    REFRESH: (state) => {
        state.filter = JSON.parse(JSON.stringify(filter));
        state.sort = JSON.parse(JSON.stringify(sort));
        state.pagination = JSON.parse(JSON.stringify(pagination));
    }
};