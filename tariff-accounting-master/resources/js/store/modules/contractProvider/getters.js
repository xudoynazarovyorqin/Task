export const getters = {
    list: state => state.list,
    inventory: state => state.inventory,
    columns: state => state.columns,
    filter: state => state.filter,
    pagination: state => state.pagination,
    sort: state => state.sort,
    model: state => state.model,
    rules: state => state.rules,
    statuses: state => state.statuses,
    contract_products: state => state.contract_products,
    contract_materials: state => state.contract_materials,
    form: state => {
        return {
            id: state.model.id,
            number: state.model.number,
            begin_date: state.model.begin_date ? state.model.begin_date : new Date(),
            sum: state.model.sum,
            comment: state.model.comment,
            provider_id: state.model.provider ? state.model.provider.id : '',
            parent_id: state.model.parent ? state.model.parent.id : '',
            status_id: state.model.status ? state.model.status.id : '',
        }
    }

};