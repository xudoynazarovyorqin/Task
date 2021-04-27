export const getters = {
    list: state => state.list,
    inventory: state => state.inventory,
    last_id: state => state.last_id,
    model: state => state.model,
    rules: state => state.rules,
    items: state => state.items,
    columns: state => state.columns,
    filter: state => state.filter,
    pagination: state => state.pagination,
    sort: state => state.sort,
    sale_product: state => {
        return {
            quantity: 1,
            remainder: 0,
            product: null,
            currency_id: null,
            rate: 0,
        }
    },
    form: state => {
        return {
            id: state.model.id,
            number: state.model.number,
            datetime: state.model.datetime ? state.model.datetime : new Date(),
            client_id: state.model.client ? state.model.client.id : null,
            contract_client_id: state.model.contract_client ? state.model.contract_client.id : null,
            state_id: state.model.state ? state.model.state.id : null,
        }
    }
};