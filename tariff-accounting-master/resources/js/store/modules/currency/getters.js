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
            active: state.model.active ? true : false,
            symbol: state.model.symbol,
            name: state.model.name,
            rate: state.model.rate,
            reversed_rate: state.model.reversed_rate,
            reverse: state.model.reverse ? true : false
        }
    }
};