import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/paymentTypes',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/paymentTypes/inventory',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/paymentTypes/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/paymentTypes',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/paymentTypes/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/paymentTypes/${id}`,
        method: 'delete',
    })
}