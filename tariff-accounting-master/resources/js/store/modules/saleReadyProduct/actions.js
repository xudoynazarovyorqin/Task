import {
    index,
    inventory,
    show,
    store,
    update,
    deleteProduct,
    chart,
    destroy,
    multiDelete,
    print,
    getLastId
} from '@/api/saleReadyProducts';

export const actions = {

    index({ commit }, params = {}) {
        return new Promise((resolve, reject) => {
            index(params).then(res => {
                commit("SET_LIST", res.data.result.data.saleReadyProducts);
                commit("UPDATE_PAGINATION", { key: 'total', value: res.data.result.data.pagination.total });
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },
    inventory({ commit }, params = {}) {
        return new Promise((resolve, reject) => {
            inventory(params).then(res => {
                commit("SET_INVENTORY", res.data.result.data.saleReadyProducts);
                resolve(res.data.result);
            }).catch(err => {
                reject(err.response.data);
            })
        })
    },
    chart({ commit }, type) {
        return new Promise((resolve, reject) => {
            chart({ type: type }).then(res => {
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    show({ commit }, id) {
        return new Promise((resolve, reject) => {
            show(id).then(res => {
                commit('SET_MODEL', res.data.result.sale);
                resolve(res)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    store({ commit }, data) {
        return new Promise((resolve, reject) => {
            store(data).then(res => {
                commit('INCREMENT_LAST_ID');
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    empty({ commit }) {
        return new Promise((resolve, reject) => {
            commit("EMPTY_MODEL");
            resolve()
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

    deleteProduct({ commit }, data) {
        return new Promise((resolve, reject) => {
            deleteProduct(data)
                .then(res => {
                    resolve(res.data.result)
                })
                .catch(err => {
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
    multiDelete({ commit }, items) {
        return new Promise((resolve, reject) => {
            multiDelete(items).then(res => {
                resolve(res.data.result)
            }).catch(err => {
                reject(err)
            })
        })
    },
    print({ commit }, data) {
        return new Promise((resolve, reject) => {
            print(data)
                .then(res => {
                    resolve(res)
                })
                .catch(err => {
                    reject(err.response.data)
                })
        })
    },
    getLastId(ctx) {
        return new Promise((resolve, reject) => {
            getLastId()
                .then(res => {
                    ctx.commit('SET_LAST_ID', res.data.result.last_id)
                    resolve(res.data.result)
                })
                .catch(err => {
                    reject(err.response.data)
                })
        });
    },

    clearFilter({ commit }) {
        commit("CLEAR_FILTER");
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
            commit("REFRESH")
            resolve()
        })
    }
}