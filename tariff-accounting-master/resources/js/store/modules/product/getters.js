export const getters = {
    list: state => state.list,
    inventory: state => state.inventory,
    semi_product_list: state => state.semi_product_list,
    columns: state => state.columns,
    filter: state => state.filter,
    pagination: state => state.pagination,
    sort: state => state.sort,
    model: state => state.model,
    rules: state => state.rules,
    product_material: state => state.product_material,
    product_materials: state => state.product_materials,
    semi_product: state => state.semi_product,
    semi_products: state => state.semi_products,
    form: state => {
        return {
            id: state.model.id,
            name: state.model.name,
            code: state.model.code,
            nds: state.model.nds,
            categories: state.model.categories,
            purchase_price: state.model.purchase_price,
            purchase_currency_id: state.model.purchase_currency ? state.model.purchase_currency.id : null,
            selling_price: state.model.selling_price,
            selling_currency_id: state.model.selling_currency ? state.model.selling_currency.id : null,
            description: state.model.description,
            measurement_id: state.model.measurement ? state.model.measurement.id : null,
            vendor_code: state.model.vendor_code,
            country_id: state.model.country ? state.model.country.id : null,
            warehouse_type_id: state.model.warehouse_type ? state.model.warehouse_type.id : null,
            production: state.model.production ? true : false,
            production_type: state.model.production_type,
            recycled: state.model.recycled
        }
    }
};