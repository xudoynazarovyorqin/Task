import request from './../utils/request'

  export function index(params) {
  	return request({
  	  url: '/defect_products',
  	  method: 'get',
        params
  	})
  }

  export function show(id) {
    return request({
      url: `/defect_products/${id}`,
      method: 'get'
    })
  }
  export function history(id) {
    return request({
      url: `/defect_products/history/${id}`,
      method: 'get'
    })
  }


  export function store(data) {
    return request({
      url: '/defect_products',
      method: 'post',
      data
    })
  }

  export function update(data) {
    return request({
      url: `/defect_products/${data.id}`,
      method: 'put',
      data
    })
  }

  export function destroy(id) {
    return request({
      url: `/defect_products/${id}`,
      method: 'delete',
    })
  }

  export function createDefectFromShipment(data) {
    return request({
      url: '/defect_products/create/from/shipment',
      method: 'post',
      data
    })
  }

  export function getShipment(data) {
    return request({
      url: '/defect_products/get/shipment',
      method: 'post',
      data
    })
  }
