import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/products',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/products/inventory',
        method: 'get',
        params
    })
}

export function show(id, params) {
    return request({
        url: `/products/${id}`,
        method: 'get',
        params
    })
}

export function copy(data) {
    return request({
        url: `/products/copy`,
        method: 'post',
        data
    })
}

export function store(data) {
    return request({
        url: '/products',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/products/${data.id}`,
        method: 'put',
        data
    })
}

export function remainder(params) {
    return request({
        url: `products/remainder`,
        method: 'get',
        params
    })
}

export function destroy(id) {
    return request({
        url: `/products/${id}`,
        method: 'delete',
    })
}