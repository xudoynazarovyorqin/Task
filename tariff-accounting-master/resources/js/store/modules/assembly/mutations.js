import { model } from "./properties/model";
import { filter } from "./properties/filter";
import { sort } from "../properties/sort";
import { pagination } from "../properties/pagination";

export const mutations = {
    SET_LIST: (state, assemblies) => {
        state.list = []
        for (const key in assemblies) {
            if (assemblies.hasOwnProperty(key)) {
                const element = assemblies[key];
                element.owner = (element.owner == 'firm') ? 'Фирма' : 'Клиент';
                element.assemblyable_type = (element.assemblyable_type == 'orders') ? 'Заказы на производство' : 'Для склад';
                state.list.push(element)
            }
        }
    },
    SET_FILTER: (state, filter) => (state.filter = filter),
    CLEAR_FILTER: (state) => (state.filter = JSON.parse(JSON.stringify(filter))),
    SET_PAGINATION: (state, pagination) => (state.pagination = pagination),
    SET_SORT: (state, sort) => (state.sort = sort),
    SET_LAST_ID: (state, lastId) => (state.lastId = lastId),
    INCREMENT_LAST_ID: (state) => (++state.lastId),
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

        if (payload.assembly) {
            const { assembly } = payload;
            /**
             * Set main data assembly
             */
            state.model = assembly;
            /**
             * Set assembly items
             */
            state.assembly_items = [];
            if (assembly.assembly_items) {
                const { assembly_items } = assembly;
                assembly_items.forEach(element => {
                    state.assembly_items.push(element)
                });
            }
            /**
             * Set assembly additional materials
             */
            state.additional_materials = [];
            if (assembly.additional_materials) {
                const { additional_materials } = assembly;
                additional_materials.forEach(element => {
                    state.additional_materials.push(element)
                });
            }
            /**
             *   Set audits
             */
            state.created_audit = {};
            if (assembly.created_audit) {
                const { created_audit } = assembly;
                state.created_audit = created_audit;
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
    ASSEMBLY_ITEMS: (state, payload) => {
        /*
         *   Set assembly items
         */
        state.assembly_items = [];
        if (payload.assembly_items) {
            const { assembly_items } = payload;
            assembly_items.forEach(element => {
                state.assembly_items.push(element);
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
        state.assembly_items = [];
        state.additional_materials = [];
        state.employeeGroups = [];
        state.manufactured_products = [];
    },
    REFRESH: (state) => {
        state.filter = JSON.parse(JSON.stringify(filter));
        state.sort = JSON.parse(JSON.stringify(sort));
        state.pagination = JSON.parse(JSON.stringify(pagination));
    }
};