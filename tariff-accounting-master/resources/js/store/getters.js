export const getters = {
    money: state => state.money,
    money_product: state => state.money_product,
    money_material: state => state.money_material,
    number_product: state => state.number_product,
    number_material: state => state.number_material,
    token: state => state.auth.token,
    name: state => state.auth.name,
    phone: state => state.auth.phone,
    role: state => state.auth.role,
    current_tab: state => state.current_tab
}