export const getters = {
    list: state => state.list,
    inventory: state => state.inventory,
    columns: state => state.columns,
    filter: state => state.filter,
    pagination: state => state.pagination,
    sort: state => state.sort,
    model: state => state.model,
    contract_client_suspenses: state => state.contract_client_suspenses,
    rules: state => state.rules,
    form: state => {
        return {
            id: state.model.id,
            number: state.model.number,
            begin_date: state.model.begin_date ? state.model.begin_date : new Date(),
            sum: state.model.sum,
            conclusion_date: state.model.conclusion_date ? state.model.conclusion_date : '',
            termination_date: state.model.termination_date ? state.model.termination_date : '',
            comment: state.model.comment,
            client_id: state.model.client ? state.model.client.id : '',
            parent_id: state.model.parent ? state.model.parent.id : '',
            status_id: state.model.status ? state.model.status.id : '',
        }
    }
};
