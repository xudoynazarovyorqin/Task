import request from '@/utils/request';

export function index(params) {
    return request({
        url: '/transactions',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/transactions/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/transactions',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/transactions/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/transactions/${id}`,
        method: 'delete',
    })
}

export function getLastId() {
    return request({
        url: `/transactions/getLastId`,
        method: 'get',
    })
}

export function getApplicationDocument(data) {
    return request({
        url: '/transactions/get/application/document',
        method: 'post',
        data
    })
}

export function saveReturnTransaction(data) {
    return request({
        url: '/transactions/save/return/transaction',
        method: 'post',
        data
    })
}

export function multiDelete(data) {
    return request({
        url: `/transactions/multipleDelete`,
        method: 'post',
        data
    })
}

export function getAmountsAndCounts() {
    return request({
        url: `/transactions/get/amounts/and/counts`,
        method: 'get',
    })
}
