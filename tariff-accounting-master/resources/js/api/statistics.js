import request from './../utils/request'


export function productStatistic(params) {
  	return request({
  	  url: '/statistics/product',
  	  method: 'get',
      params
  	})
  }

  export function materialStatistic(params) {
    return request({
      url: '/statistics/material',
      method: 'get',
      params
    })
  }