import { productStatistic, materialStatistic } from '@/api/statistics';

export const actions = {

    productStatistic({ commit }, data) {
        return new Promise((resolve, reject) => {
            productStatistic({ type: data.type, product_id: data.product_id }).then(res => {
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    materialStatistic({ commit }, data) {
        return new Promise((resolve, reject) => {
            materialStatistic({ type: data.type, material_id: data.material_id }).then(res => {
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },
};