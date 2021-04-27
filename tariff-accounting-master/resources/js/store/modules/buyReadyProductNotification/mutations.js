import { buy_model } from "./properties/buy_model";
import { filter } from "./properties/filter";
import { sort } from "../properties/sort";
import { pagination } from "../properties/pagination";

export const mutations = {
    SET_LIST: (state, buy_notifications) => (state.list = buy_notifications),
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
    SET_CREATE_BUY: (state, notification) => {
        /**
         * set main data
         */
        state.buy_model.status_id = "1";
        state.buy_model.is_warehouse = 0;
        state.buy_model.object_type = notification.buy_ready_product_notificationable_type;
        state.buy_model.object_id = notification.buy_ready_product_notificationable_id;
        state.buy_model.buy_notification_id = notification.id;

        /**
         * set buy products
         * @type {Array}
         */
        state.buy_products = [];
        if (notification.products) {
            for (let key in notification.products) {
                if (notification.products.hasOwnProperty(key)) {
                    let buy_product = notification.products[key];

                    let yes = state.buy_products.filter(function(item) {
                        return item.product_id == buy_product.product_id;
                    });

                    // agar mavjud product chqib qolsa faqat qty siga qoshish
                    if (yes.length) {
                        for (let key2 in state.buy_products) {
                            if (state.buy_products.hasOwnProperty(key2)) {
                                if (state.buy_products[key2].product_id == buy_product.product_id) {
                                    state.buy_products[key2].qty_weight = parseFloat(state.buy_products[key2].qty_weight) + parseFloat(buy_product.quantity);
                                    state.buy_products[key2].min_qty_weight = state.buy_products[key2].qty_weight;
                                }
                            }
                        }
                    }

                    //aks holda yengi qilib qoshish
                    else {
                        state.buy_products.push({
                            product_id: (buy_product.product) ? buy_product.product.id : null,
                            product_name: (buy_product.product) ? buy_product.product.name : '',
                            qty_weight: parseFloat(buy_product.quantity),
                            min_qty_weight: parseFloat(buy_product.quantity),
                            currency_id: 1,
                            currency_symbol: 'UZS',
                            rate: 1,
                            buy_price: (buy_product.product) ? parseFloat(buy_product.product.purchase_price) : 0,
                            selling_price: (buy_product.product) ? parseFloat(buy_product.product.selling_price) : 0,
                            total_price: (buy_product.product) ? parseFloat(parseFloat(buy_product.product.purchase_price) * parseFloat(buy_product.quantity)) : 0,
                            measurement: (buy_product.product.measurement) ? buy_product.product.measurement.name : "",
                        })
                    }

                }
            }
        }
    },

    EMPTY_MODEL: (state) => {
        state.buy_model = JSON.parse(JSON.stringify(buy_model));
        state.buy_products = [];
    },

    REFRESH: (state) => {
        state.filter = JSON.parse(JSON.stringify(filter));
        state.sort = JSON.parse(JSON.stringify(sort));
        state.pagination = JSON.parse(JSON.stringify(pagination));
    }
};