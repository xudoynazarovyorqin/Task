export const getters = {
    list: state => state.list,
    inventory: state => state.inventory,
    reworking_materials: state => state.reworking_materials,
    columns: state => state.columns,
    filter: state => state.filter,
    pagination: state => state.pagination,
    sort: state => state.sort,
    model: state => state.model,
    rules: state => state.rules,
    types: state => state.types,
    form: state => {
        return {
            id: state.model.id,
            name: state.model.name,
            code: state.model.code,
            sku: state.model.sku,
            price: state.model.price,
            price_currency_id: state.model.price_currency ? state.model.price_currency.id : null,
            critical_weight: state.model.critical_weight,
            is_active: state.model.is_active,
            is_reworking: state.model.is_reworking,
            measurement_id: state.model.measurement ? state.model.measurement.id : null,
            measurement_changeable: state.model.measurement_changeable,
            additional_measurement_id: state.model.additional_measurement ? state.model.additional_measurement.id : null,
            additional_measurement_rate: state.model.additional_measurement_rate,
            country_id: state.model.country ? state.model.country.id : null,
            warehouse_type_id: state.model.warehouse_type ? state.model.warehouse_type.id : null,
        }
    }
};