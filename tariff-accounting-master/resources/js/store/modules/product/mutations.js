import { filter } from "./properties/filter";
import { sort } from "../properties/sort";
import { pagination } from "../properties/pagination";
import { model } from "./properties/model";

export const mutations = {
    SET_LIST: (state, products) => {
        state.list = [];
        products.forEach(product => {
            product.recycled = (product.recycled) ? 'Переработанный' : 'Не переработанный';
            product.production = (product.production) ? 'Да' : 'Нет';
            product.production_type = (product.production_type == 'production') ? 'Полуготовый продукт' : (product.production_type == 'assembly') ? 'Готовый продукт' : '';
            state.list.push(product);
        });
    },
    SET_INVENTORY: (state, products) => (state.inventory = products),
    SET_SEMI_PRODUCTS: (state, products) => (state.semi_product_list = products),
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
    SET_MODEL: (state, model) => {
        state.model = model;
        /**
         * set product materials
         */
        state.product_materials = [];
        if (model.materials) {
            let materials = model.materials.data;
            for (let key in materials) {
                if (materials.hasOwnProperty(key)) {
                    let product_material = materials[key];
                    state.product_materials.push({
                        id: product_material.id,
                        material: product_material.material,
                        quantity: product_material.quantity,
                        inverse_quantity: product_material.inverse_quantity
                    })
                }
            }
        }
        /**
         * set semi_products
         */
        state.semi_products = [];
        if (model.semi_products) {
            let semi_products = model.semi_products.data;
            for (let key in semi_products) {
                if (semi_products.hasOwnProperty(key)) {
                    let item = semi_products[key];
                    state.semi_products.push({
                        id: item.id,
                        product: (item.semi_product) ? item.semi_product : null,
                        quantity: item.quantity
                    })
                }
            }
        }

        let model_categories = JSON.parse(JSON.stringify(model.categories));
        state.model.categories = [];
        if (model_categories) {            
            for (let key in model_categories) {
                if (model_categories.hasOwnProperty(key)) {
                    state.model.categories.push(model_categories[key].id)
                }
            }
        }

    },
    EMPTY_MODEL: (state) => {
        state.model = JSON.parse(JSON.stringify(model));
        state.product_materials = [];
        state.semi_products = [];
    },
    REFRESH: (state) => {
        state.filter = JSON.parse(JSON.stringify(filter));
        state.sort = JSON.parse(JSON.stringify(sort));
        state.pagination = JSON.parse(JSON.stringify(pagination));
    }
};