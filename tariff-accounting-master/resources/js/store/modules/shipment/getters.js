export const getters = {
    list: state => state.list,
    lastId: state => state.lastId,
    shipment_products: state => state.shipment_products,
    model: state => state.model,
    rules: state => state.rules,
    columns: state => state.columns,
    filter: state => state.filter,
    pagination: state => state.pagination,
    sort: state => state.sort,
    shipmentable_types: state => state.shipmentable_types,
    selectedRow: state => state.selectedRow,
    form: state => {
        return {
            id: state.model.id,
            datetime: state.model.datetime ? state.model.datetime : new Date(),
            shipmentable_type: state.model.shipmentable_type,
            shipmentable_id: state.model.shipmentable_id,
            user_id: state.model.user ? state.model.user.id : null,
            comment: state.model.comment,
        }
    }
};