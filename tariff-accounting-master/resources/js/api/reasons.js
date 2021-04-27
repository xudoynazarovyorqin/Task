import request from '@/utils/request'


export function index(params) {
    return request({
        url: '/reasons',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/reasons/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/reasons',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/reasons/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/reasons/${id}`,
        method: 'delete',
    })
}