const NumberMoneyProduct = 'number_money_product';
const NumberMoneyMaterial = 'number_money_material';
const NumberQuantityProduct = 'number_quantity_product';
const NumberQuantityMaterial = 'number_quantity_material';

export function setNumberMoneyProduct(number_money_product) {
    return localStorage.setItem(NumberMoneyProduct, number_money_product)
}

export function setNumberMoneyMaterial(number_money_material) {
    return localStorage.setItem(NumberMoneyMaterial, number_money_material)
}

export function setNumberQuantityProduct(number_quantity_product) {
    return localStorage.setItem(NumberQuantityProduct, number_quantity_product)
}

export function setNumberQuantityMaterial(number_quantity_material) {
    return localStorage.setItem(NumberQuantityMaterial, number_quantity_material)
}

export function getNumberMoneyProduct() {
    return localStorage.getItem(NumberMoneyProduct)
}

export function getNumberMoneyMaterial() {
    return localStorage.getItem(NumberMoneyMaterial)
}

export function getNumberQuantityProduct() {
    return localStorage.getItem(NumberQuantityProduct)
}

export function getNumberQuantityMaterial() {
    return localStorage.getItem(NumberQuantityMaterial)
}

export function getCurrentTab() {
    return localStorage.getItem('current_tab');
}