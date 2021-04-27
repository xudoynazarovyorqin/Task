export const getters = {
    list: state => state.list,
    inventory: state => state.inventory,
    lastId: state => state.lastId,
    statuses: state => state.statuses,
    model: state => state.model,
    rules: state => state.rules,
    buy_materials: state => state.buy_materials,
    warehouse_materials: state => state.warehouse_materials,
    columns: state => state.columns,
    filter: state => state.filter,
    pagination: state => state.pagination,
    sort: state => state.sort,
    buy_material: state => {
        return {
            material_id: null,
            qty_weight: 1,
            currency_id: 1,
            rate: 1,
            price: 0,
        }
    },
    form: state => {
        return {
            id: state.model.id,
            number: state.model.number,
            datetime: state.model.datetime ? state.model.datetime : new Date(),
            date: state.model.date ? state.model.date : new Date(),
            provider_id: state.model.provider ? state.model.provider.id : null,
            contract_provider_id: state.model.contract_provider ? state.model.contract_provider.id : null,
            status_id: state.model.status ? state.model.status.id : null,
            comment: state.model.comment,
            is_warehouse: state.model.is_warehouse,
            object_type: state.model.object_type,
            object_id: state.model.object_id,
            buy_notification_id: state.model.buy_notification_id,
        }
    }
};