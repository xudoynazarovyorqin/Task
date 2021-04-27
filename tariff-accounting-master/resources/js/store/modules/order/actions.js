import {
    index,
    inventory,
    store,
    show,
    edit,
    update,
    destroy,
    chart,
    print,
    getLastId,
    loadComments,
    commentsStore,
    checkDate,
    deleteAdditionalMaterial,
    deleteProduct,
    deleteCost,
    multiDelete
} from '@/api/orders';

export const actions = {

    index({ commit }, params = {}) {
        return new Promise((resolve, reject) => {
            index(params).then(res => {
                commit("SET_LIST", res.data.result.data.orders);
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
                commit("SET_INVENTORY", res.data.result.data.orders);
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

    edit({ commit }, id) {
        return new Promise((resolve, reject) => {
            edit(id).then(res => {
                commit('EDIT_MODEL', res.data.result.data);
                resolve(res)
            }).catch(err => {
                console.log(err);
                reject(err.response.data)
            })
        })
    },

    show({ commit }, id) {
        return new Promise((resolve, reject) => {
            show(id).then(res => {
                commit('EDIT_MODEL', res.data.result.data);
                resolve(res)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    update({ commit }, payload) {
        return new Promise((resolve, reject) => {
            update(payload).then(res => {
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

    checkDate({ commit }, payload) {
        return new Promise((resolve, reject) => {
            checkDate(payload).then(res => {
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    deleteProduct({ commit }, payload) {
        return new Promise((resolve, reject) => {
            deleteProduct(payload).then(res => {
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    deleteAdditionalMaterial({ commit }, payload) {
        return new Promise((resolve, reject) => {
            deleteAdditionalMaterial(payload).then(res => {
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    deleteCost({ commit }, payload) {
        return new Promise((resolve, reject) => {
            deleteCost(payload).then(res => {
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

    loadComments({ commit }, data) {
        return new Promise((resolve, reject) => {
            loadComments(data).then(res => {
                commit("SET_COMMENTS", res.data.result.data)
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    commentStore({ commit }, data) {
        return new Promise((resolve, reject) => {
            commentsStore(data).then(res => {
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
    empty({ commit }) {
        commit("empty");
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