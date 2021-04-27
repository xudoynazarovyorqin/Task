import request from '@/utils/request'

export function chart(params) {
    return request({
        url: '/saleReadyProducts/chart',
        method: 'get',
        params
    })
}


export function index(params) {
    return request({
        url: '/saleReadyProducts',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/saleReadyProducts/inventory',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/saleReadyProducts/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/saleReadyProducts',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/saleReadyProducts/${data.id}`,
        method: 'put',
        data
    })
}

export function deleteProduct(data) {
    return request({
        url: `/saleReadyProducts/deleteProduct`,
        method: 'post',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/saleReadyProducts/${id}`,
        method: 'delete',
    })
}


export function multiDelete(data) {
    return request({
        url: `/saleReadyProducts/multipleDelete`,
        method: 'post',
        data
    })
}

export function print(params) {
    return request({
        url: `/saleReadyProducts/print`,
        method: 'get',
        params
    })
}

export function getLastId() {
    return request({
        url: `/saleReadyProducts/getLastId`,
        method: 'get',
    })
}