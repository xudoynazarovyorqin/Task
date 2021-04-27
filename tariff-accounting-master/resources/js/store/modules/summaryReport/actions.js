import {
    summaryReport,
} from '@/api/summaryReports';

export const actions = {

    index({ commit }, params = {}) {
        return new Promise((resolve, reject) => {
            summaryReport(params).then(res => {
                // commit("SET_LIST", res.data.result.data.applications);
                // commit("SET_SUMS", { total_amount: res.data.result.data.total_amount, total_paid: res.data.result.data.total_paid });
                // commit("SET_COUNTS", res.data.result.data.counts);
                // commit("UPDATE_PAGINATION", { key: 'total', value: res.data.result.data.pagination.total });
                resolve(res.data.result)
            }).catch(err => {
                reject(err.response.data)
            })
        })
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
