import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/audits',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/audits/${id}`,
        method: 'get'
    })
}

export function downloadChanges(id) {
    return request({
        url: `/download/change/values/${id}`,
        method: 'get',
        responseType: 'blob'
    })
}

export function auditList() {
    return request({
        url: '/audits/auditList',
        method: 'get',
    })
}
