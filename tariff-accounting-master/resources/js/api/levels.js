import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/levels',
        method: 'get',
        params
    })
}
export function inventory(params) {
    return request({
        url: '/levels/inventory',
        method: 'get',
        params
    })
}
export function show(id) {
    return request({
        url: `/levels/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/levels',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/levels/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/levels/${id}`,
        method: 'delete',
    })
}