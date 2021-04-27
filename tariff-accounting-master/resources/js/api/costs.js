import request from './../utils/request'


export function index(params) {
    return request({
        url: '/costs',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/costs/inventory',
        method: 'get',
        params
    })
}

export function chart(params) {
    return request({
        url: '/costs/chart',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/costs/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/costs',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/costs/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/costs/${id}`,
        method: 'delete',
    })
}