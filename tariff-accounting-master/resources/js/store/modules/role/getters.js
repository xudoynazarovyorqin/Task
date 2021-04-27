export const getters = {
    list: state => state.list,
    inventory: state => state.inventory,
    model: state => state.model,
    rules: state => state.rules,
    columns: state => state.columns,
    filter: state => state.filter,
    pagination: state => state.pagination,
    sort: state => state.sort,
    permissions: state => state.permissions,
    form: state => {
        return {
            id: state.model.id,
            name: state.model.name,
            slug: state.model.slug,
        }
    }
};