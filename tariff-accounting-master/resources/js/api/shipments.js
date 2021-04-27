import request from '@/utils/request'


export function index(params) {
    return request({
        url: '/shipments',
        method: 'get',
        params
    })
}

export function show(id) {
    return request({
        url: `/shipments/${id}`,
        method: 'get'
    })
}

export function store(data) {
    return request({
        url: '/shipments',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/shipments/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/shipments/${id}`,
        method: 'delete',
    })
}

export function multiDelete(data) {
    return request({
        url: `/shipments/multipleDelete`,
        method: 'post',
        data
    })
}

export function print(params) {
    return request({
        url: `/shipments/print`,
        method: 'get',
        params
    })
}

export function getDocuments(params) {
    return request({
        url: `/shipments/documents`,
        method: 'get',
        params
    })
}

export function getLastId() {
    return request({
        url: `/shipments/getLastId`,
        method: 'get',
    })
}

export function getReservations(params) {
    return request({
        url: `/shipments/reservations`,
        method: 'get',
        params
    })
}

export function items(params) {
    return request({
        url: `/shipments/items`,
        method: 'get',
        params
    })
}