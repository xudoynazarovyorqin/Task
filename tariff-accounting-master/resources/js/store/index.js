import Vue from 'vue'
import Vuex from 'vuex'
import modules from './modules'
import { state } from './state'
import { actions } from './actions'
import { plugin } from './plugins'
import { getters } from './getters'
import { mutations } from './mutations'

Vue.use(Vuex);

const store = new Vuex.Store({
    strict: true,
    modules: modules,
    plugins: [plugin],
    state: state,
    actions: actions,
    getters: getters,
    mutations: mutations
});

export default store;