export const getters = {
    list: state => state.list,
    inventory: state => state.inventory,
    columns: state => state.columns,
    filter: state => state.filter,
    pagination: state => state.pagination,
    sort: state => state.sort,
    model: state => state.model,
    rules: state => state.rules,
    form: state => {
        return {
            id: state.model.id,
            name: state.model.name,
            branch_name: state.model.branch_name,
            mfo: state.model.mfo,
            number: state.model.number,
            active: state.model.active,
            currency_id: state.model.currency ? state.model.currency.id : null
        }
    }
};