import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/settings',
        method: 'get',
        params
    })
}

export function store(data) {
    return request({
        url: '/settings',
        method: 'post',
        data
    })
}