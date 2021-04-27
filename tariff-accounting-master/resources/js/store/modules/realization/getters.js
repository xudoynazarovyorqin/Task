export const getters = {
    list: state => state.list,
    lastId: state => state.lastId,
    columns: state => state.columns,
    filter: state => state.filter,
    pagination: state => state.pagination,
    sort: state => state.sort,
    model: state => state.model,
    rules: state => state.rules,
    realization_types: state => state.realization_types,
    selectedRow: state => state.selectedRow,
    realization_materials: state => state.realization_materials,
    form: state => {
        return {
            id: state.model.id,
            datetime: state.model.datetime ? state.model.datetime : new Date(),
            realizationable_type: state.model.realizationable_type,
            realizationable_id: state.model.realizationable_id,
            user_id: state.model.user ? state.model.user.id : null,
        }
    }
};