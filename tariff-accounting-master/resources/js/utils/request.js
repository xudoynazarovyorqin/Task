import axios from 'axios'
import store from '@/store/index'
import router from '@/router/router'
import app from '@/app'; // import the instance

// create an axios instance
const service = axios.create({
    baseURL: '/api/', // url = base url + request url
    // withCredentials: true, // send cookies when cross-domain requests
    timeout: 40000 // request timeout
});

// request interceptor
service.interceptors.request.use(
    config => {
        // do something before request is sent

        if (store.getters.token) {
            // let each request carry token
            // ['X-Token'] is a custom headers key
            // please modify it according to the actual situation
            config.headers['Authorization'] = "Bearer " + store.getters.token
        }
        app.$Progress.start(); // for every request start the progress
        return config
    },
    error => {
        // do something with request error
        return Promise.reject(error)
    }
);

// response interceptor
service.interceptors.response.use(
    /**
     * If you want to get http information such as headers or status
     * Please return  response => response
     */

    /**
     * Determine the request status by custom code
     * Here is just an example
     * You can also judge the status by HTTP Status Code
     */
    response => {
        app.$Progress.finish(); // finish when a response is received
        // if the custom code is not 200, it is judged as an error.
        if (response.status !== 200) {
            return Promise.reject(response)
        } else {
            return Promise.resolve(response)
        }
    },
    error => {
        app.$Progress.fail(); // finish when a response is received
        // 500: Unexpected error; 401: Unuthenticated;
        let response = error.response;
        if (response.status == 401) {
            //removeToken();
            store.dispatch('auth/resetToken')
            router.push('/login')
        }
        return Promise.reject(error)
    }
);

export default service