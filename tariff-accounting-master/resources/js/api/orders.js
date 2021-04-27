import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/orders',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/orders/inventory',
        method: 'get',
        params
    })
}

export function print(params) {
    return request({
        url: `/orders/print`,
        method: 'get',
        params
    })
}
export function chart(params) {
    return request({
        url: '/orders/chart',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/orders/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/orders',
        method: 'post',
        data
    })
}

export function edit(id) {
    return request({
        url: `/orders/${id}/edit`,
        method: 'get'
    })
}

export function update(data) {
    return request({
        url: `/orders/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/orders/${id}`,
        method: 'delete',
    })
}

export function checkDate(data) {
    return request({
        url: `/orders/checkDate`,
        method: 'post',
        data
    })
}

export function deleteAdditionalMaterial(data) {
    return request({
        url: `/orders/deleteAdditionalMaterial`,
        method: 'post',
        data
    })
}

export function deleteProduct(data) {
    return request({
        url: `/orders/deleteProduct`,
        method: 'post',
        data
    })
}

export function deleteCost(data) {
    return request({
        url: `/orders/deleteCost`,
        method: 'post',
        data
    })
}


export function loadComments(params) {
    return request({
        url: `/orders/comments`,
        method: 'get',
        params
    })
}

export function commentsStore(data) {
    return request({
        url: `/orders/comments`,
        method: 'post',
        data
    })
}


export function multiDelete(data) {
    return request({
        url: `/orders/multipleDelete`,
        method: 'post',
        data
    })
}

export function getLastId() {
    return request({
        url: `/orders/getLastId`,
        method: 'get',
    })
}