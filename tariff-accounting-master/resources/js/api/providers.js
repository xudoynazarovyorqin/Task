import request from './../utils/request'

export function index(params) {
    return request({
        url: '/providers',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/providers/inventory',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/providers/${id}`,
        method: 'get'
    })
}

export function validation(data) {
    return request({
        url: `/providers/validation`,
        method: 'post',
        data
    })
}

export function store(data) {
    return request({
        url: '/providers',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/providers/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/providers/${id}`,
        method: 'delete',
    })
}

export function getTypes() {
    return request({
        url: '/providers/get/types',
        method: 'get',
    })
}