import {
    index,
    show,
    edit,
    store,
    update,
    loadReport,
    reportShow,
    deleteProduct,
    deleteAdditionalMaterial,
    loadComments,
    commentsStore,
    historyStore,
    print,
    destroy,
    multiDelete,
    getDefectProducts,
    getSaleProducts,
    getManufacturedProducts,
    backMaterialsToWarehouse,
    manufacturedStore,
    defectStore,
    getLastId,
    deleteDefectProduct
} from '@/api/sales';

export const actions = {

    index({ commit }, params = {}) {
        return new Promise((resolve, reject) => {
            index(params).then(res => {
                commit("SET_LIST", res.data.result.data.sales);
                commit("UPDATE_PAGINATION", { key: 'total', value: res.data.result.data.pagination.total });
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    show({ commit }, id) {
        return new Promise((resolve, reject) => {
            show(id).then(res => {
                commit('EDIT', res.data.result.data);
                resolve(res)
            }).catch(err => {
                reject(err.response.data)
            })
        });
    },

    edit({ commit }, id) {
        return new Promise((resolve, reject) => {
            edit(id).then(res => {
                commit('EDIT', res.data.result.data);
                resolve(res)
            }).catch(err => {
                console.log(err);
                reject(err.response.data)
            })
        });
    },

    store({ commit }, data) {
        return new Promise((resolve, reject) => {
            store(data).then(res => {
                commit('INCREMENT_LAST_ID');
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        });
    },

    loadReport({ commit }, data) {
        return new Promise((resolve, reject) => {
            loadReport(data).then(res => {
                resolve(res)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    reportShow({ commit }, data) {
        return new Promise((resolve, reject) => {
            reportShow(data).then(res => {
                resolve(res)
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

    historyStore({ commit }, data) {
        return new Promise((resolve, reject) => {
            historyStore(data)
                .then(res => {
                    resolve(res.data.result)
                })
                .catch(err => {
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

    printSaleMaterials({ commit }, data) {
        return new Promise((resolve, reject) => {
            printSaleMaterials(data)
                .then(res => {
                    resolve(res)
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
        });
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

    getSaleProducts({ commit }, payload) {
        return new Promise((resolve, reject) => {
            getSaleProducts(payload).then(res => {
                commit('SALE_PRODUCTS', res.data.result.data);
                resolve(res.data.result)
            }).catch(err => {
                reject(err)
            })
        });
    },

    getManufacturedProducts({ commit }, payload) {
        return new Promise((resolve, reject) => {
            getManufacturedProducts(payload).then(res => {
                commit('MANUFACTURED_PRODUCTS', res.data.result.data);
                resolve(res.data.result)
            }).catch(err => {
                reject(err)
            })
        });
    },

    getDefectProducts({ commit }, payload) {
        return new Promise((resolve, reject) => {
            getDefectProducts(payload).then(res => {
                commit('DEFECT_PRODUCTS', res.data.result.data);
                resolve(res.data.result)
            }).catch(err => {
                reject(err)
            })
        });
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
    deleteAdditionalMaterial({ commit }, data) {
        return new Promise((resolve, reject) => {
            deleteAdditionalMaterial(data)
                .then(res => {
                    resolve(res.data.result)
                })
                .catch(err => {
                    reject(err)
                })
        })
    },

    backMaterialsToWarehouse({ commit }, data) {
        return new Promise((resolve, reject) => {
            backMaterialsToWarehouse(data)
                .then(res => {
                    resolve(res.data.result)
                })
                .catch(err => {
                    reject(err.response.data)
                })
        })
    },

    manufacturedStore({ commit }, data) {
        return new Promise((resolve, reject) => {
            manufacturedStore(data).then(res => {
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    defectStore({ commit }, data) {
        return new Promise((resolve, reject) => {
            defectStore(data).then(res => {
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
    },

    deleteDefectProduct({ commit }, data) {
        return new Promise((resolve, reject) => {
            deleteDefectProduct(data).then(res => {
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
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