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
            is_active: state.model.is_active,
        }
    }
};