import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/countries',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/countries/inventory',
        method: 'get',
        params
    })
}

export function search(data) {
    return request({
        url: '/countries/live/search',
        method: 'post',
        data
    })
}

export function show(id) {
    return request({
        url: `/countries/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/countries',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/countries/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/countries/${id}`,
        method: 'delete',
    })
}