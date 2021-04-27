import { index, comingProducts } from '@/api/warehouseProducts';

export const actions = {
    index({ commit }, params = {}) {
        return new Promise((resolve, reject) => {
            index(params).then(res => {
                commit("SET_LIST", res.data.result.data.warehouseProducts);
                commit("UPDATE_PAGINATION", { key: 'total', value: res.data.result.data.pagination.total });
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },
    comingProducts({ commit }, data) {
        return new Promise((resolve, reject) => {
            comingProducts(data).then(res => {
                commit("SET_COMING_PRODUCTS", res.data.result.data.warehouse_products.data);
                resolve(res.data.result)
            }).catch(err => {
                console.log(err);
                reject(err.response.data)
            })
        })
    },
    updateSort({ commit }, sort) {
        commit("SET_SORT", sort)
    },

    updateFilter({ commit }, filter) {
        commit('SET_FILTER', JSON.parse(JSON.stringify(filter)))
    },

    updateColumn({ commit }, obj) {
        commit('UPDATE_COLUMN', obj)
    },

    updatePagination({ commit }, pagination) {
        commit('UPDATE_PAGINATION', pagination)
    },

    refreshData({ commit }) {
        return new Promise((resolve, reject) => {
            commit("REFRESH");
            resolve()
        })
    }


}