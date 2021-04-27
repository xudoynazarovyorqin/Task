import {buy_model} from "./properties/buy_model";
import {filter} from "./properties/filter";
import {sort} from "../properties/sort";
import {pagination} from "../properties/pagination";

export const mutations = {
    SET_LIST: (state, buy_notifications) => (state.list = buy_notifications),
    SET_FILTER: (state,filter) => (state.filter = filter),
    SET_PAGINATION: (state,pagination) => (state.pagination = pagination),
    SET_SORT: (state,sort) => (state.sort = sort),
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
        state.buy_model.object_type = notification.buy_notificationable_type;
        state.buy_model.object_id = notification.buy_notificationable_id;
        state.buy_model.buy_notification_id = notification.id;
        

        /**
         * set buy materials
         * @type {Array}
         */
        state.buy_materials = [];
        if (notification.materials)
        {
            for (let key in notification.materials)
            {
                if (notification.materials.hasOwnProperty(key))
                {
                    let buy_material = notification.materials[key];

                    let yes = state.buy_materials.filter(function(item) {
                        return item.material_id == buy_material.material_id;
                    });

                    // agar mavjud material chqib qolsa faqat qty siga qoshish
                    if( yes.length )
                    {
                        for (let key2 in state.buy_materials)
                        {
                            if (state.buy_materials.hasOwnProperty(key2))
                            {
                                if( state.buy_materials[key2].material_id == buy_material.material_id )
                                {
                                    state.buy_materials[key2].qty_weight = parseFloat(state.buy_materials[key2].qty_weight) + parseFloat(buy_material.quantity);
                                    state.buy_materials[key2].min_qty_weight = state.buy_materials[key2].qty_weight;
                                }
                            }
                        }
                    }

                    //aks holda yengi qilib qoshish
                    else
                    {
                        state.buy_materials.push({
                            material_id: (buy_material.material) ? buy_material.material.id : null,
                            material_name: (buy_material.material) ? buy_material.material.name : '',
                            qty_weight: parseFloat(buy_material.quantity),
                            min_qty_weight: parseFloat(buy_material.quantity),
                            currency_id: 1,
                            currency_symbol: 'UZS',
                            rate: 1,
                            price: (buy_material.material) ? parseFloat(buy_material.material.price) : 0,
                            total_price:(buy_material.material) ? parseFloat(parseFloat(buy_material.material.price) * parseFloat(buy_material.quantity)) : 0,
                            measurement: (buy_material.material && buy_material.material.measurement) ? buy_material.material.measurement.name : "",                        
                        })                        
                    }

                }
            }
        }
    },

    EMPTY_MODEL: (state) => {
        state.buy_model = JSON.parse( JSON.stringify( buy_model ));
        state.buy_materials = [];
    },

    REFRESH: (state) => {
        state.filter = JSON.parse( JSON.stringify( filter ));
        state.sort = JSON.parse( JSON.stringify( sort ));
        state.pagination = JSON.parse( JSON.stringify( pagination ));
    }
};
