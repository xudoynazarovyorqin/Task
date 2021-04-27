import { index, store } from '@/api/settings';
import { setNumberMoneyProduct, setNumberMoneyMaterial, setNumberQuantityProduct, setNumberQuantityMaterial } from "@/utils/local_storage";

export const actions = {

    index({ commit }, params = {}) {
        return new Promise((resolve, reject) => {
            index(params).then(res => {
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    store({ commit }, data) {
        return new Promise((resolve, reject) => {
            store(data).then(res => {
                const { number_money_product, number_money_material, number_quantity_product, number_quantity_material } = res.data.result.data
                setNumberMoneyProduct(number_money_product)
                setNumberMoneyMaterial(number_money_material)
                setNumberQuantityProduct(number_quantity_product)
                setNumberQuantityMaterial(number_quantity_material)
                commit("SET_CANGES", { number_money_product: number_money_product, number_money_material: number_money_material, number_quantity_product: number_quantity_product, number_quantity_material: number_quantity_material }, { root: true })
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },
};