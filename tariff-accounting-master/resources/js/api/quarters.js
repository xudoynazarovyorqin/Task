import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/quarters',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/quarters/inventory',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/quarters/${id}`,
        method: 'get',
    })
}

export function store(data) {
    return request({
        url: '/quarters',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/quarters/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/quarters/${id}`,
        method: 'delete',
    })
}
