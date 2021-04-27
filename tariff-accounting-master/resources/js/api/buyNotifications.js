import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/buyNotifications',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `buyNotifications/${id}`,
        method: 'get'
    })
}

export function cancel(data) {
    return request({
        url: '/buyNotifications/cancel',
        method: 'post',
        data
    })
}

export function count() {
    return request({
        url: '/buyNotifications/count',
        method: 'get',
    })
}