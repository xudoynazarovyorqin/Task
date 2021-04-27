import request from '@/utils/request'

export function index(params) {
    return request({
        url: '/materials',
        method: 'get',
        params
    })
}

export function inventory(params) {
    return request({
        url: '/materials/inventory',
        method: 'get',
        params
    })
}

export function search(text) {
    let data = {
        'search': text
    }

    return request({
        url: '/materials/live/search',
        method: 'post',
        data
    })
}

export function show(id, params) {
    return request({
        url: `/materials/${id}`,
        method: 'get',
        params
    })
}

export function copy(data) {
    return request({
        url: `/materials/copy`,
        method: 'post',
        data
    })
}

export function store(data) {
    return request({
        url: '/materials',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/materials/${data.id}`,
        method: 'put',
        data
    })
}

export function destroy(id) {
    return request({
        url: `/materials/${id}`,
        method: 'delete',
    })
}

export function getTypes() {
    return request({
        url: '/materials/get/types',
        method: 'get',
    })
}

export function getReworkingMaterials() {
    return request({
        url: '/materials/get/reworking/materials',
        method: 'get',
    })
}