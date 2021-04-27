import request from '@/utils/request'

export function reportMaterials() {
    return request({
        url: '/report/materials',
        method: 'get'
    })
}

export function reportProducts() {
    return request({
        url: '/report/products',
        method: 'get'
    })
}

export function excelMaterials() {
    return request({
        url: '/report/export/excel/materials',
        method: 'get',
        responseType: 'blob'
    })
}

export function excelProducts() {
    return request({
        url: '/report/export/excel/products',
        method: 'get',
        responseType: 'blob'
    })
}