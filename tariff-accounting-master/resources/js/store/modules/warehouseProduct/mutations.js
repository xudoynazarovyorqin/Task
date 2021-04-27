import { filter } from "./properties/filter";
import { sort } from "../properties/sort";
import { pagination } from "../properties/pagination";

export const mutations = {
    SET_LIST: (state, products) => {
        state.list = []
        products.forEach(element => {
            element.cost_price = element.total_cost_price / ((element.remainder) ? element.remainder : 1);
            element.selling_price = element.total_selling_price / ((element.remainder) ? element.remainder : 1);
            state.list.push(element);
        });
    },
    SET_COMING_PRODUCTS: (state, comingProducts) => {
        state.comingProducts = [];
        comingProducts.forEach(element => {
            element.currency = element.currency;
            element.rate = element.currency ? (element.currency.reverse ? _.round(1 / element.rate, 8) : element.rate) : 1;
            element.owner = (element.owner == 'provider') ? 'Поставщик' : (element.owner == 'client') ? 'Клиент' : 'Фирма';
            element.warehouse_productable_type = (element.warehouse_productable_type == 'buy_ready_product_lists') ? 'Закупки продукции' : (element.warehouse_productable_type == 'sale_products') ? 'Производства ' : (element.warehouse_productable_type == 'assembly_items') ? 'Сборки ' : '';
            state.comingProducts.push(element);
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