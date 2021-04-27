export const mutations = {
    CHANGE_CURRENT_TAB: (state,tab) => {
        localStorage.setItem('current_tab',tab)
        state.current_tab = tab
    },
    SET_CANGES: (state, payload) => {
        state.number_material.precision = payload.number_quantity_material;
        state.number_product.precision = payload.number_quantity_product;
        state.money_material.precision = payload.number_money_material;
        state.money_product.precision = payload.number_money_product;
    }
}