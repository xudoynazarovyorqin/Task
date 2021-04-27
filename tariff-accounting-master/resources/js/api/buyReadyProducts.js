import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/buyReadyProducts',
        method: 'get',
        params
    })
}
export function inventory(params) {
    return request({
        url: '/buyReadyProducts/inventory',
        method: 'get',
        params
    })
}
export function chart(params) {
    return request({
        url: '/buyReadyProducts/chart',
        method: 'get',
        params
    })
}
export function show(id) {
    return request({
        url: `/buyReadyProducts/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/buyReadyProducts',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/buyReadyProducts/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/buyReadyProducts/${id}`,
        method: 'delete',
    })
}

export function multiDelete(data) {
    return request({
        url: `/buyReadyProducts/multipleDelete`,
        method: 'post',
        data
    })
}

export function items(params) {
    return request({
        url: `/buyReadyProducts/items`,
        method: 'get',
        params
    })
}

export function warehouse_products(params) {
    return request({
        url: '/buyReadyProducts/warehouse_products',
        method: 'get',
        params
    })
}

export function getStatuses() {
    return request({
        url: '/buyReadyProducts/get/statuses',
        method: 'get',
    })
}

export function receive(data) {
    return request({
        url: '/buyReadyProducts/receive',
        method: 'post',
        data
    })
}

export function deleteProduct(data) {
    return request({
        url: '/buyReadyProducts/product/delete',
        method: 'post',
        data
    })
}

export function print(params) {
    return request({
        url: `/buyReadyProducts/print`,
        method: 'get',
        params
    })
}
export function getLastId() {
    return request({
        url: `/buyReadyProducts/getLastId`,
        method: 'get',
    })
}