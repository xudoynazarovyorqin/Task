import request from './../utils/request'


export function index(params) {
  	return request({
  	  url: '/employeeGroups',
  	  method: 'get',
        params
  	})
  }

export function show(id) {
    return request({
      url: `/employeeGroups/${id}`,
      method: 'get'
    })
  }

  export function store(data) {
    return request({
      url: '/employeeGroups',
      method: 'post',
      data
    })
  }

  export function update(data) {
    return request({
      url: `/employeeGroups/${data.id}`,
      method: 'put',
      data
    })
  }

  export function destroy(id) {
    return request({
      url: `/employeeGroups/${id}`,
      method: 'delete',
    })
  }
