export const getters = {
    list: state => state.list,
    inventory: state => state.inventory,
    lastId: state => state.lastId,
    model: state => state.model,
    rules: state => state.rules,
    order_products: state => state.order_products,
    order_costs: state => state.order_costs,
    additional_materials: state => state.additional_materials,
    columns: state => state.columns,
    filter: state => state.filter,
    pagination: state => state.pagination,
    sort: state => state.sort,
    order_product: state => {
        return {
            quantity: 1,
            product: null,
            price: 0,
            currency_id: null,
            rate: 0,
        }
    },
    employeeGroups: state => state.employeeGroups,
    created_audit: state => state.created_audit,
    comments: state => state.comments,
    form: state => {
        return {
            id: state.model.id,
            number: state.model.number,
            production_type: state.model.production_type ? state.model.production_type : 'production',
            datetime: state.model.datetime ? state.model.datetime : new Date(),
            begin_date: state.model.begin_date ? state.model.begin_date : new Date(),
            end_date: state.model.end_date ? state.model.end_date : '',
            client_id: state.model.client ? state.model.client.id : null,
            contract_client_id: state.model.contract_client ? state.model.contract_client.id : null,
            state_id: state.model.state ? state.model.state.id : null,
            priority_id: state.model.priority ? state.model.priority.id : null,
        }
    }
};