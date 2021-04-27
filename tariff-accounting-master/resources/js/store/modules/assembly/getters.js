export const getters = {
    list: state => state.list,
    model: state => state.model,
    lastId: state => state.lastId,
    rules: state => state.rules,
    columns: state => state.columns,
    filter: state => state.filter,
    pagination: state => state.pagination,
    sort: state => state.sort,
    assembly_item: state => {
        return {
            product_id: null,
            quantity: 0,
        }
    },
    assembly_items: state => state.assembly_items,
    additional_materials: state => state.additional_materials,
    employeeGroups: state => state.employeeGroups,
    manufactured_products: state => state.manufactured_products,
    defect_products: state => state.defect_products,
    comments: state => state.comments,
    created_audit: state => state.created_audit,
    form: state => {
        return {
            id: state.model.id,
            datetime: state.model.datetime ? state.model.datetime : new Date(),
            begin_date: state.model.begin_date ? state.model.begin_date : new Date(),
            end_date: state.model.end_date ? state.model.end_date : '',
            state_id: state.model.state ? state.model.state.id : null,
            priority_id: state.model.priority ? state.model.priority.id : null,
        }
    }
};