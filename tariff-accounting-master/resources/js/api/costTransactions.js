import request from '@/utils/request'

export function index(params) {
  	return request({
		url: '/costTransactions',
		method: 'get',
        params
  	});
}