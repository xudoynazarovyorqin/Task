export const getters = {
    list: state => state.list,
    inventory: state => state.inventory,
    model: state => state.model,
    rules: state => state.rules,
    columns: state => state.columns,
    filter: state => state.filter,
    pagination: state => state.pagination,
    sort: state => state.sort,
    statues: state => state.statues,
    permissions: state => state.permissions,
    form: state => {
        return {
            id: state.model.id,
            name: state.model.name,
            first_name: state.model.first_name,
            surname: state.model.surname,
            patronymic: state.model.patronymic,
            phone: state.model.phone,
            email: state.model.email,
            status: state.model.status,
            role_id: state.model.role ? state.model.role.id : null,
            is_employee: state.model.is_employee == 1 ? true : false,
            pin_code: state.model.pin_code,
        }
    }
};