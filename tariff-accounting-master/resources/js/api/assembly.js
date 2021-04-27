import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/assembly',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/assembly/${id}`,
        method: 'get'
    })
}

export function edit(id) {
    return request({
        url: `/assembly/${id}/edit`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/assembly',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/assembly/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/assembly/${id}`,
        method: 'delete',
    })
}

export function loadReport(data) {
    return request({
        url: `/assembly/report`,
        method: 'post',
        data
    })
}

export function reportShow(data) {
    return request({
        url: `/assembly/report-show`,
        method: 'post',
        data
    })
}

export function deleteProduct(data) {
    return request({
        url: `/assembly/deleteProduct`,
        method: 'post',
        data
    })
}

export function deleteAdditionalMaterial(data) {
    return request({
        url: `/assembly/deleteAdditionalMaterial`,
        method: 'post',
        data
    })
}

export function print(params) {
    return request({
        url: `/assembly/print`,
        method: 'get',
        params
    })
}

export function multiDelete(data) {
    return request({
        url: `/assembly/multipleDelete`,
        method: 'post',
        data
    })
}

export function getAssemblyItems(data) {
    return request({
        url: `/assembly/getAssemblyItems`,
        method: 'post',
        data
    });
}

export function getManufacturedProducts(data) {
    return request({
        url: `/assembly/getManufacturedProducts`,
        method: 'post',
        data
    });
}

export function getDefectProducts(data) {
    return request({
        url: `/assembly/getDefectProducts`,
        method: 'post',
        data
    });
}

export function loadComments(params) {
    return request({
        url: `/assembly/comments`,
        method: 'get',
        params
    })
}

export function commentsStore(data) {
    return request({
        url: `/assembly/comments`,
        method: 'post',
        data
    })
}

export function manufacturedStore(data) {
    return request({
        url: `/assembly/manufacturedStore`,
        method: 'post',
        data
    })
}

export function defectStore(data) {
    return request({
        url: `/assembly/defectStore`,
        method: 'post',
        data
    })
}

export function deleteDefectProduct(data) {
    return request({
        url: `/assembly/deleteDefectProduct`,
        method: 'post',
        data
    })
}

export function getLastId() {
    return request({
        url: `/assembly/getLastId`,
        method: 'get',
    })
}