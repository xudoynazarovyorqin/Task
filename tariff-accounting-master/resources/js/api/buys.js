import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/buys',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/buys/inventory',
        method: 'get',
        params
    })
}

export function chart(params) {
    return request({
        url: '/buys/chart',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/buys/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/buys',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/buys/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/buys/${id}`,
        method: 'delete',
    })
}

export function items(params) {
    return request({
        url: `/buys/items`,
        method: 'get',
        params
    })
}

export function multiDelete(data) {
    return request({
        url: `/buys/multipleDelete`,
        method: 'post',
        data
    })
}

export function warehouse_materials(params) {
    return request({
        url: '/buys/warehouse_materials',
        method: 'get',
        params
    })
}

export function getStatuses() {
    return request({
        url: '/buys/get/statuses',
        method: 'get',
    })
}

export function receive(data) {
    return request({
        url: '/buys/receive',
        method: 'post',
        data
    })
}

export function deleteMaterial(data) {
    return request({
        url: '/buy/material/delete',
        method: 'post',
        data
    })
}

export function print(params) {
    return request({
        url: `/buys/print`,
        method: 'get',
        params
    })
}

export function getLastId() {
    return request({
        url: `/buys/getLastId`,
        method: 'get',
    })
}