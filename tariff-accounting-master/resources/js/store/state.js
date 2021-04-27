import { getNumberMoneyProduct, getNumberMoneyMaterial, getNumberQuantityProduct, getNumberQuantityMaterial , getCurrentTab} from '@/utils/local_storage';

export const state =  {
    money: {
        precision: 2,
    },
    money_product: {
        precision:  _.isNumber(_.parseInt(getNumberMoneyProduct())) ? _.parseInt(getNumberMoneyProduct()) : 2,
    },
    money_material: {
        precision: _.isNumber(_.parseInt(getNumberMoneyMaterial())) ? _.parseInt(getNumberMoneyMaterial()) : 2,
    },
    number_product: {
        precision: _.isNumber(_.parseInt(getNumberQuantityProduct())) ? _.parseInt(getNumberQuantityProduct()) : 2,
    },
    number_material: {
        precision: _.isNumber(_.parseInt(getNumberQuantityMaterial())) ? _.parseInt(getNumberQuantityMaterial()) : 2,
    },
    current_tab: getCurrentTab()
}
