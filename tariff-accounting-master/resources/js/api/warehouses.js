import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/warehouses',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/warehouses/inventory',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/warehouses/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/warehouses',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/warehouses/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/warehouses/${id}`,
        method: 'delete',
    })
}

export function types() {
    return request({
        url: `/all/types`,
        method: 'get',
    })
}