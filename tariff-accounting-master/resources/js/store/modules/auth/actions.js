import { login, logout, refresh, getAuth } from "@/api/auth";
import { setToken, removeToken } from "@/utils/auth";
import { setNumberMoneyProduct, setNumberMoneyMaterial, setNumberQuantityProduct, setNumberQuantityMaterial } from "@/utils/local_storage";

export const actions = {

    login({ commit }, credentials) {
        const { phone, password } = credentials;
        return new Promise((resolve, reject) => {
            login({ phone: phone.trim(), password: password }).then(response => {
                const { data } = response.data.result;
                if (data.token) {
                    commit('SET_TOKEN', data.token)
                    setToken(data.token)
                    resolve()
                }
            }).catch(error => {
                reject(error)
            })
        })
    },

    getAuth({ commit, state }, payload) {
        return new Promise((resolve, reject) => {
            getAuth(state.token).then(res => {
                const { data } = res.data.result

                if (!data) {
                    reject('Проверка не удалась, пожалуйста, войдите снова.')
                }

                const { role, name, phone, number_money_product, number_money_material, number_quantity_product, number_quantity_material } = data

                if (!role) {
                    reject('Роль не может быть пустой')
                }

                setNumberMoneyProduct(number_money_product)
                setNumberMoneyMaterial(number_money_material)
                setNumberQuantityProduct(number_quantity_product)
                setNumberQuantityMaterial(number_quantity_material)
                commit('SET_ROLE', role)
                commit('SET_NAME', name)
                commit('SET_PHONE', phone)
                resolve(data)
            }).catch(error => {
                commit('SET_TOKEN', '')
                removeToken();
                reject(error);
            })
        })
    },

    refresh({ commit }) {
        refresh().then(res => {
            const { data } = res.data.result;
            if (data.token) {
                commit('SET_TOKEN', data.token)
                setToken(data.token)
            }
        }).catch(err => {
            removeToken()
        })

    },

    logout({ commit, state }) {
        return new Promise((resolve, reject) => {
            logout().then(() => {
                removeToken()
                commit('SET_TOKEN', '')
                commit('SET_ROLE', '')
                resolve()
            }).catch(error => {
                reject(error)
            })
        })
    },

    resetToken({ commit }) {
        return new Promise((resolve) => {
            removeToken()
            commit('SET_TOKEN', '')
            resolve()
        })
    },
}