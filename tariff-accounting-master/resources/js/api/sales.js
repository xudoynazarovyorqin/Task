import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/sales',
        method: 'get',
        params
    })
}

export function edit(id) {
    return request({
        url: `/sales/${id}/edit`,
        method: 'get'
    })
}

export function show(id) {
    return request({
        url: `/sales/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/sales',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/sales/${data.id}`,
        method: 'put',
        data
    })
}

export function loadReport(data) {
    return request({
        url: `/sales/report`,
        method: 'post',
        data
    })
}

export function reportShow(data) {
    return request({
        url: `/sales/report-show`,
        method: 'post',
        data
    })
}

export function deleteProduct(data) {
    return request({
        url: `/sales/deleteProduct`,
        method: 'post',
        data
    })
}

export function print(params) {
    return request({
        url: `/sales/print`,
        method: 'get',
        params
    })
}

export function historyStore(data) {
    return request({
        url: `/sales/histories/store`,
        method: 'post',
        data
    })
}

export function deleteAdditionalMaterial(data) {
    return request({
        url: `/sales/deleteAdditionalMaterial`,
        method: 'post',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/sales/${id}`,
        method: 'delete',
    })
}

export function loadComments(params) {
    return request({
        url: `/sales/comments`,
        method: 'get',
        params
    })
}

export function commentsStore(data) {
    return request({
        url: `/sales/comments`,
        method: 'post',
        data
    })
}

export function backMaterialsToWarehouse(data) {
    return request({
        url: `/sales/backMaterialsToWarehouse`,
        method: 'post',
        data
    })
}

export function multiDelete(data) {
    return request({
        url: `/sales/multipleDelete`,
        method: 'post',
        data
    })
}

export function getSaleProducts(data) {
    return request({
        url: `/sales/getSaleProducts`,
        method: 'post',
        data
    })
}

export function getDefectProducts(data) {
    return request({
        url: `/sales/getDefectProducts`,
        method: 'post',
        data
    })
}

export function getManufacturedProducts(data) {
    return request({
        url: `/sales/getManufacturedProducts`,
        method: 'post',
        data
    })
}

export function manufacturedStore(data) {
    return request({
        url: `/sales/manufacturedStore`,
        method: 'post',
        data
    })
}

export function defectStore(data) {
    return request({
        url: `/sales/defectStore`,
        method: 'post',
        data
    })
}

export function deleteDefectProduct(data) {
    return request({
        url: `/sales/deleteDefectProduct`,
        method: 'post',
        data
    })
}

export function getLastId() {
    return request({
        url: `/sales/getLastId`,
        method: 'get',
    })
}