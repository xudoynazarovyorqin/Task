import request from './../utils/request'

export function index(params) {
    return request({
        url: '/users',
        method: 'get',
        params: params
    })
}


export function inventory(params) {
    return request({
        url: '/users/inventory',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/users/${id}`,
        method: 'get'
    })
}

export function validation(data) {
    return request({
        url: '/users/validation',
        method: 'post',
        data
    })
}

export function store(data) {
    return request({
        url: '/users',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/users/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/users/${id}`,
        method: 'delete',
    })
}