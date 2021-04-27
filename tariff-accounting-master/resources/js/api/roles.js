import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/roles',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/roles/inventory',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/roles/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/roles',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/roles/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/roles/${id}`,
        method: 'delete',
    })
}