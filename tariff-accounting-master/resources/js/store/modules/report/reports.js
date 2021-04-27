import { reportMaterials, reportProducts, excelMaterials, excelProducts } from '@/api/reports';

const actions = {

    reportMaterials({ commit }) {
        return new Promise((resolve, reject) => {
            reportMaterials().then(res => {
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    reportProducts({ commit }) {
        return new Promise((resolve, reject) => {
            reportProducts().then(res => {
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    excelMaterials({ commit }) {
        return new Promise((resolve, reject) => {
            excelMaterials().then(res => {
                resolve(res)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    excelProducts({ commit }) {
        return new Promise((resolve, reject) => {
            excelProducts().then(res => {
                resolve(res)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },
}

export default {
    namespaced: true,
    actions
}