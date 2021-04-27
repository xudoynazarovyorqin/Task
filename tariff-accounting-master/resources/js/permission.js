import router from './router/router'
import { getToken } from './utils/auth' // get token from cookie
import getPageTitle from './utils/get-page-title'
import store from "./store/index";
import { Notification } from 'element-ui'

router.beforeEach(async(to, from, next) => {

    // set page title
    document.title = getPageTitle(to.meta.title);

    // determine whether the user has logged in
    const hasToken = (store.getters.token != null) ? store.getters.token : getToken();
    if (hasToken) {
        if (to.path === '/login') {
            // if is logged in, redirect to the home page
            next({ path: '/' })
        } else {
            // determine whether the user has obtained his permission roles through getInfo
            const hasRole = store.getters.role
            if (hasRole && hasRole != undefined) {
                next()
            } else {
                try {
                    // get user info
                    // note: roles must be a object array! such as: ['admin'] or ,['developer','editor']
                    await store.dispatch('auth/getAuth')
                        .then(res => {})
                        .catch(async(err) => {
                            next(`/login?redirect=${to.path}`)
                            await store.dispatch('auth/resetToken')
                        })
                    next()
                } catch (error) {
                    // remove token and go to login page to re-login
                    await store.dispatch('auth/resetToken')
                    Notification.error(error || 'Has Error')
                    next(`/login?redirect=${to.path}`)
                }
            }
        }
    } else {
        /* has no token*/
        if (to.path == '/login') {
            // in the free login whitelist, go directly
            next()
        } else {
            // other pages that do not have permission to access are redirected to the login page.
            next(`/login?redirect=${to.path}`)
        }
    }
});

router.afterEach(() => {
    // finish progress bar
});