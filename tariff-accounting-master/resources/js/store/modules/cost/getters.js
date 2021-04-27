export const getters = {
    list: state => state.list,
    inventory: state => state.inventory,
    columns: state => state.columns,
    model: state => state.model,
    filter: state => state.filter,
    pagination: state => state.pagination,
    sort: state => state.sort,
    rules: state => state.rules,
    form: state => {
        return {
            id: state.model.id,
            name: state.model.name,
            amount: state.model.amount,
            currency_id: state.model.currency ? state.model.currency.id : null,
            description: state.model.description,
            is_distribution: state.model.is_distribution,
        }
    }
};