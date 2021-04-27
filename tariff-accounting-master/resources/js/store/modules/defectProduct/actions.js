import { index, show, store, update, destroy, history, createDefectFromShipment, getShipment } from '@/api/defect_products';

export const actions = {

    index({ commit }, params = {}) {
        return new Promise((resolve, reject) => {
            index(params).then(res => {
                commit("SET_LIST", res.data.result.data.defect_products.data);
                commit("UPDATE_PAGINATION", { key: 'total', value: res.data.result.data.defect_products.pagination.total });
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    show({ commit }, id) {
        return new Promise((resolve, reject) => {
            show(id).then(res => {
                commit("SET_MODEL", res.data.result.data.defect_product);
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    history({ commit }, id) {
        return new Promise((resolve, reject) => {
            history(id).then(res => {
                commit("SET_HISTORY", res.data.result.data.defect_products);
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    store({ commit }, data) {
        return new Promise((resolve, reject) => {
            store(data).then(res => {
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    update({ commit }, data) {
        return new Promise((resolve, reject) => {
            update(data).then(res => {
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    destroy({ commit }, id) {
        return new Promise((resolve, reject) => {
            destroy(id).then(res => {
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    createDefectFromShipment({ commit }, data) {
        return new Promise((resolve, reject) => {
            createDefectFromShipment(data).then(res => {
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    getShipment({ commit }, data) {
        return new Promise((resolve, reject) => {
            getShipment(data).then(res => {
                commit("SET_SHIPMENT_PRODUCTS", res.data.result.data.object);
                commit('SET_OLD_DEFECT_PRODUCTS', res.data.result.data.old_defect_products);
                resolve(res.data.result)
            }).catch(err => {
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
    },
    empty({ commit }) {
        return new Promise((resolve, reject) => {
            commit("EMPTY_MODEL");
            resolve()
        })
    },
}