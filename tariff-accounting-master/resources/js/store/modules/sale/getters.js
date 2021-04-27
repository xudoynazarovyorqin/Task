export const getters = {

    columns: state => state.columns,
    filter: state => state.filter,
    pagination: state => state.pagination,
    sort: state => state.sort,
    rules: state => state.rules,

    list: state => state.list,
    lastId: state => state.lastId,
    model: state => state.model,
    sale_products: state => state.sale_products,
    additional_materials: state => state.additional_materials,
    sale_product: state => {
        return {
            quantity: 1,
            product: null,
        }
    },
    employeeGroups: state => state.employeeGroups,

    histories: state => state.histories,
    created_info: state => state.created_info,
    comments: state => state.comments,
    defect_products: state => state.defect_products,
    manufactured_products: state => state.manufactured_products,
    form: state => {
        return {
            id: state.model.id,
            number: state.model.number,
            datetime: state.model.datetime ? state.model.datetime : new Date(),
            begin_date: state.model.begin_date ? state.model.begin_date : new Date(),
            end_date: state.model.end_date ? state.model.end_date : '',
            state_id: state.model.state ? state.model.state.id : null,
            priority_id: state.model.priority ? state.model.priority.id : null,
            level_id: state.model.level ? state.model.level.id : null,
        }
    }
};