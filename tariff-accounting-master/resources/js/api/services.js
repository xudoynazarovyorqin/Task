import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/services',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/services/inventory',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/services/${id}`,
        method: 'get',
    })
}

export function store(data) {
    return request({
        url: '/services',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/services/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/services/${id}`,
        method: 'delete',
    })
}