import { index, inventory, show, store, update, destroy, getLastId, items, warehouse_products, getStatuses, chart, deleteProduct, receive, multiDelete, print } from '@/api/buyReadyProducts';

export const actions = {
    index({ commit }, params = {}) {
        return new Promise((resolve, reject) => {
            index(params).then(res => {
                commit("SET_LIST", res.data.result.data.buys);
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
                commit("SET_INVENTORY", res.data.result.data.buys);
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
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
                commit('SET_MODEL', res.data.result.data.buy);
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

    items({ commit }, params) {
        return new Promise((resolve, reject) => {
            items(params).then(res => {
                resolve(res.data.result)
            }).catch(err => {
                reject(err)
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

    warehouse_products({ commit }, params) {
        return new Promise((resolve, reject) => {
            warehouse_products(params).then(res => {
                commit("SET_WAREHOUSE_PRODUCTS", res.data.result.warehouse_products.data);
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    getStatuses({ commit }) {
        return new Promise((resolve, reject) => {
            getStatuses().then(res => {
                commit("SET_STATUSES", res.data.result.data.statuses);
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    receive({ commit }, data) {
        return new Promise((resolve, reject) => {
            receive(data).then(res => {
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    deleteProduct({ commit }, data) {
        return new Promise((resolve, reject) => {
            deleteProduct(data).then(res => {
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
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

    empty({ commit }) {
        return new Promise((resolve, reject) => {
            commit("EMPTY_MODEL");
            resolve()
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


}
