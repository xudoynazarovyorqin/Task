import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/applications',
        method: 'get',
        params
    })
}

export function print(params) {
    return request({
        url: `/applications/print`,
        method: 'get',
        params
    })
}
export function chart(params) {
    return request({
        url: '/applications/chart',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/applications/${id}`,
        method: 'get'
    })
}

export function getAudits(id) {
    return request({
        url: `/applications/${id}/get/audits`,
        method: 'get'
    })
}

export function getParts(id) {
    return request({
        url: `/applications/${id}/get/parts`,
        method: 'get'
    })
}

export function getTransactions(id) {
    return request({
        url: `/applications/${id}/get/transactions`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/applications',
        method: 'post',
        data
    })
}

export function edit(id) {
    return request({
        url: `/applications/${id}/edit`,
        method: 'get'
    })
}

export function update(data) {
    return request({
        url: `/applications/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/applications/${id}`,
        method: 'delete',
    })
}

export function deleteService(data) {
    return request({
        url: `/applications/deleteService`,
        method: 'post',
        data
    })
}

export function multiDelete(data) {
    return request({
        url: `/applications/multipleDelete`,
        method: 'post',
        data
    })
}

export function getLastId() {
    return request({
        url: `/applications/getLastId`,
        method: 'get',
    })
}

export function debitListExcel(params) {
    return request({
        url: '/applications/debit/list/excel',
        method: 'get',
        responseType: 'blob',
        params
    })
}
