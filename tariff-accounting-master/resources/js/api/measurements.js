import request from './../utils/request'

export function index(params) {
    return request({
        url: '/measurements',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/measurements/inventory',
        method: 'get',
        params
    })
}

export function search(data) {
    return request({
        url: '/measurements',
        method: 'post',
        params
    })
}

export function show(id) {
    return request({
        url: `/measurements/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/measurements',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/measurements/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/measurements/${id}`,
        method: 'delete',
    })
}