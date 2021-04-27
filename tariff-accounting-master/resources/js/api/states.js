import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/states',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/states/inventory',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/states/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/states',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/states/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/states/${id}`,
        method: 'delete',
    })
}