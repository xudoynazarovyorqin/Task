import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/applicationParts',
        method: 'get',
        params
    })
}
