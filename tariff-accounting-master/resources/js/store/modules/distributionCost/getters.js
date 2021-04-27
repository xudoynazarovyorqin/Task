export const getters = {
    list: state => state.list,
    lastId: state => state.lastId,
    columns: state => state.columns,
    model: state => state.model,
    filter: state => state.filter,
    pagination: state => state.pagination,
    sort: state => state.sort,
    rules: state => state.rules,
    items: state => state.items,
    transactions: state => state.transactions,
    old_transactions: state => state.old_transactions,
    form: state => {
        return {
            id: state.model.id,
            type: state.model.type,
            datetime: state.model.datetime ? state.model.datetime : new Date(),
            from_date: (state.model.from_date) ? state.model.from_date : '',
            to_date: (state.model.to_date) ? state.model.to_date : '',
        }
    }
};