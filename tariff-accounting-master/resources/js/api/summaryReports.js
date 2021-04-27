import request from '@/utils/request'

export function summaryReport(params) {
    return request({
        url: '/applications/summary/report',
        method: 'get',
        params
    })
}
