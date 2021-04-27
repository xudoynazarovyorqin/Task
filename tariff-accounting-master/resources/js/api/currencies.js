import request from './../utils/request'


export function index(params) {
    return request({
        url: '/currencies',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/currencies/inventory',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/currencies/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/currencies',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/currencies/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/currencies/${id}`,
        method: 'delete',
    })
}