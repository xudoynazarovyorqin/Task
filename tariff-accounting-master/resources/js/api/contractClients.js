import request from './../utils/request'

export function index(params) {
    return request({
        url: '/contractClients',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/contractClients/inventory',
        method: 'get',
        params
    })
}

export function search(data) {
    return request({
        url: '/contractClients/live/search',
        method: 'post',
        data
    })
}

export function show(id) {
    return request({
        url: `/contractClients/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/contractClients',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/contractClients/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/contractClients/${id}`,
        method: 'delete',
    })
}

export function deleteSuspense(data) {
    return request({
        url: 'contractClients/delete/suspense',
        method: 'post',
        data
    })
}

export function getStatuses() {
    return request({
        url: '/contractClients/get/statuses',
        method: 'get',
    })
}

export function updateProducts(data) {
    return request({
        url: '/contractClients/update/products',
        method: 'post',
        data
    })
}

export function print(params) {
    return request({
        url: `/contractClients/print`,
        method: 'get',
        params
    })
}
