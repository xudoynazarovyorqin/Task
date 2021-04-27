import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/warehouseTypes',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/warehouseTypes/inventory',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/warehouseTypes/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/warehouseTypes',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/warehouseTypes/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/warehouseTypes/${id}`,
        method: 'delete',
    })
}