import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/categories',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/categories/inventory',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/categories/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/categories',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/categories/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/categories/${id}`,
        method: 'delete',
    })
}