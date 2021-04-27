import request from './../utils/request'

export function index(params) {
    return request({
        url: '/warehouseProducts',
        method: 'get',
        params
    })
}

export function comingProducts(data) {
    return request({
        url: '/warehouseProducts/coming/products',
        method: 'post',
        data
    })
}