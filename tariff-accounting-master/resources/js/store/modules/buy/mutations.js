import { filter } from "./properties/filter";
import { sort } from "../properties/sort";
import { pagination } from "../properties/pagination";
import { model } from "./properties/model";
import { i18n } from '@/utils/modules/i18n';

export const mutations = {
    SET_LIST: (state, buys) => {
        state.list = [];
        for (const key in buys) {
            if (buys.hasOwnProperty(key)) {
                const element = buys[key];
                element.not_paid = element.total_price - element.paid_price;
                element.paid = (element.paid) ? i18n.t('message.paid') : i18n.t('message.not_paid');
                element.object_type = (element.object_type == 'sales') ? i18n.t('message.sale') : (element.object_type == 'assemblies') ? i18n.t('message.assembly') : '';
                element.is_warehouse = (element.is_warehouse) ? i18n.t('message.yes') : i18n.t('message.no');
                element.contract_provider_id = (element.contract_provider) ? ('№' + element.contract_provider.number + ' ' + i18n.t('message.from') + ' ' + element.contract_provider.begin_date) : '';
                state.list.push(element)
            }
        }
    },
    SET_INVENTORY: (state, buys) => (state.inventory = buys),
    SET_STATUSES: (state, statuses) => (state.statuses = statuses),
    SET_FILTER: (state, filter) => (state.filter = filter),
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
    SET_MODEL: (state, model) => {
        /**
         * Set main data
         */
        state.model = model;
        /**
         * Set buy materials
         * @type {Array}
         */
        state.buy_materials = [];
        if (model.buy_materials) {
            _.forEach(model.buy_materials, function(item) {
                state.buy_materials.push({
                    id: item.id,
                    material: item.material,
                    qty_weight: parseFloat(item.qty_weight),
                    not_enough: parseFloat(item.not_enough),
                    currency: item.currency,
                    rate: item.currency ? (item.currency.reverse ? _.round(1 / item.rate, 8) : item.rate) : 1,
                    price: parseFloat(item.price),
                })
            })
        }
    },
    SET_WAREHOUSE_MATERIALS: (state, warehouse_materials) => {
        state.warehouse_materials = [];
        warehouse_materials.forEach(element => {
            element.rate = element.currency ? (element.currency.reverse ? _.round(1 / element.rate) : element.rate) : 1,
                state.warehouse_materials.push(element);
        });
    },
    EMPTY_MODEL: (state) => {
        state.model = JSON.parse(JSON.stringify(model));
        state.buy_materials = [];
    },

    REFRESH: (state) => {
        state.filter = JSON.parse(JSON.stringify(filter));
        state.sort = JSON.parse(JSON.stringify(sort));
        state.pagination = JSON.parse(JSON.stringify(pagination));
    }
};