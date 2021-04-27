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
            color: state.model.color,
            right: state.model.right ? state.model.right.id : null,
            left: state.model.left ? state.model.left.id : null,
        }
    }
};