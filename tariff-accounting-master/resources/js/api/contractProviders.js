import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/contractProviders',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/contractProviders/inventory',
        method: 'get',
        params
    })
}

export function search(data) {
    return request({
        url: '/contractProviders/live/search',
        method: 'post',
        data
    })
}

export function show(id) {
    return request({
        url: `/contractProviders/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/contractProviders',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/contractProviders/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/contractProviders/${id}`,
        method: 'delete',
    })
}

export function getStatuses() {
    return request({
        url: '/contractProviders/get/statuses',
        method: 'get',
    })
}

export function updateMaterials(data) {
    return request({
        url: '/contractProviders/update/materials',
        method: 'post',
        data
    })
}