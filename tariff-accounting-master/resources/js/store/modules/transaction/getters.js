export const getters = {
    list: state => state.list,
    lastId: state => state.lastId,
    columns: state => state.columns,
    model: state => state.model,
    filter: state => state.filter,
    pagination: state => state.pagination,
    sort: state => state.sort,
    rules: state => state.rules,
    payments: state => state.payments,
    relatedItems: state => state.relatedItems,
    form: state => {
        return {
            id: state.model.id,
            amount: state.model.amount,
            comment: state.model.comment,
            transactionable_id: state.model.transaction ? state.model.transaction.id : null,
        }
    },
    payment_systems: state => state.payment_systems,
    transaction_states: state => state.transaction_states,
    transaction_amounts: state => state.transaction_amounts,
};
