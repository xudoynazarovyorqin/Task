import request from './../utils/request'

export function index(params) {
    return request({
        url: '/priority',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/priority/inventory',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/priority/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/priority',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/priority/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/priority/${id}`,
        method: 'delete',
    })
}