import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/scores',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/scores/inventory',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/scores/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/scores',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/scores/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/scores/${id}`,
        method: 'delete',
    })
}