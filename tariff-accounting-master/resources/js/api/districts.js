import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/districts',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/districts/inventory',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/districts/${id}`,
        method: 'get',
    })
}

export function store(data) {
    return request({
        url: '/districts',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/districts/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/districts/${id}`,
        method: 'delete',
    })
}
