export const getters = {
    list: state => state.list,
    lastId: state => state.lastId,
    total_amount: state => state.total_amount,
    total_paid: state => state.total_paid,
    application_counts: state => state.application_counts,
    model: state => state.model,
    rules: state => state.rules,
    columns: state => state.columns,
    filter: state => state.filter,
    pagination: state => state.pagination,
    sort: state => state.sort,
    application_services: state => state.application_services,
    application_service: state => {
        return {
            service: null,
            price: 0,
        }
    },
    audits: state => state.audits,
    form: state => {
        return {
            id: state.model.id,
            number: state.model.number,
            datetime: state.model.datetime ? state.model.datetime : new Date(),
            client_id: state.model.client ? state.model.client.id : null,
            contract_client_id: state.model.contract_client ? state.model.contract_client.id : null,
            status_id: state.model.status ? state.model.status.id : null,
            console_number: state.model.console_number,
            object_name: state.model.object_name,
            district_id: state.model.district ? state.model.district.id : null,
            object_street: state.model.object_street,
            object_home: state.model.object_home,
            object_corps: state.model.object_corps,
            object_flat: state.model.object_flat,
        }
    }
};
