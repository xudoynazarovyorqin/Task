import request from './../utils/request'


export function index(params) {
  	return request({
  	  url: '/userAuthLogs',
  	  method: 'get',
        params
  	})
  }

  export function destroy(id) {
    return request({
      url: `/userAuthLogs/${id}`,
      method: 'delete',
    })
  }
