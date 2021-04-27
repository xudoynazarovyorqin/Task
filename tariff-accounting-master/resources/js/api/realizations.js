import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/realizations',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/realizations/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/realizations',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/realizations/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/realizations/${id}`,
        method: 'delete',
    })
}

export function items(params) {
    return request({
        url: `/realizations/items`,
        method: 'get',
        params
    })
}

export function getDocuments(params) {
    return request({
        url: `/realizations/documents`,
        method: 'get',
        params
    })
}

export function getLastId() {
    return request({
        url: `/realizations/getLastId`,
        method: 'get',
    })
}

export function loadReservations(params) {
    return request({
        url: `/realizations/reservations`,
        method: 'get',
        params
    })
}

export function multiDelete(data) {
    return request({
        url: `/realizations/multipleDelete`,
        method: 'post',
        data
    })
}