export const getters = {
    list: state => state.list,
    columns: state => state.columns,
    filter: state => state.filter,
    pagination: state => state.pagination,
    sort: state => state.sort,
    model: state => state.model,
    rules: state => state.rules,
    parent_permissions: state => state.parent_permissions,
    form: state => {
        return {
            id: state.model.id,
            name: state.model.name
        }
    }
};