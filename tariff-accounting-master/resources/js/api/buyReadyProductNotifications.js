import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/buyReadyProductNotifications',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `buyReadyProductNotifications/${id}`,
        method: 'get'
    })
}

export function cancel(data) {
    return request({
        url: '/buyReadyProductNotifications/cancel',
        method: 'post',
        data
    })
}

export function count() {
    return request({
        url: '/buyReadyProductNotifications/count',
        method: 'get',
    })
}