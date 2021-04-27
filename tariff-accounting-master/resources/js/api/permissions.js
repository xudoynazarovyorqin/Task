import request from './../utils/request'

  export function index(params) {
  	return request({
  	  url: '/permissions',
      method: 'get',
        params
  	})
  }

  export function show(id) {
    return request({
      url: `/permissions/${id}`,
      method: 'get'
    })
  }

  export function store(data) {
    return request({
      url: '/permissions',
      method: 'post',
      data
    })
  }

  export function update(data) {
    return request({
      url: `/permissions/${data.id}`,
      method: 'put',
      data
    })
  }

  export function destroy(id) {
    return request({
      url: `/permissions/${id}`,
      method: 'delete',
    })
  }

  export function parent_permissions() {
      return request({
          url: '/permissions/parents',
          method: 'get'
      })
  }
