import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/clients',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/clients/inventory',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/clients/${id}`,
        method: 'get'
    })
}

export function validation(data) {
    return request({
        url: `/clients/validation`,
        method: 'post',
        data
    })
}

export function store(data) {
    return request({
        url: '/clients',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/clients/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/clients/${id}`,
        method: 'delete',
    })
}

export function getTypes() {
    return request({
        url: '/clients/get/types',
        method: 'get',
    })
}

export function getObjectData(id) {
    return request({
        url: `/clients/get/object/data/${id}`,
        method: 'get'
    })
}
