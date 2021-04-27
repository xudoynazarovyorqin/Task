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
            state: state.model.state,
            status: state.model.status,
        }
    }
};