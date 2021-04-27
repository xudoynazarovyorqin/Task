import request from '@/utils/request';

export function index(params) {
    return request({
        url: '/distributionCosts',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/distributionCosts/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/distributionCosts',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/distributionCosts/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/distributionCosts/${id}`,
        method: 'delete',
    })
}

export function getLastId() {
    return request({
        url: `/distributionCosts/getLastId`,
        method: 'get',
    })
}

export function getWarehouseProducts(params) {
    return request({
        url: '/distributionCosts/warehouseProducts',
        method: 'get',
        params
    })
}

export function getWarehouseMaterials(params) {
    return request({
        url: '/distributionCosts/warehouseMaterials',
        method: 'get',
        params
    })
}

export function getCostTransactions(params) {
    return request({
        url: '/distributionCosts/costTransactions',
        method: 'get',
        params
    })
}

export function multiDelete(data) {
    return request({
        url: `/distributionCosts/multipleDelete`,
        method: 'post',
        data
    })
}