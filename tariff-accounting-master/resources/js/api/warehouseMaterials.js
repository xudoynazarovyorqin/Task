import request from './../utils/request'

export function index(params) {
    return request({
        url: '/warehouseMaterials',
        method: 'get',
        params
    })
  }

  export function comingMaterials(data) {
    return request({
      url: '/warehouseMaterials/coming/materials',
      method: 'post',
      data
    })
  }

  export function createComing(data) {
    return request({
      url: '/warehouseMaterials/create/coming',
      method: 'post',
      data
    })
  }