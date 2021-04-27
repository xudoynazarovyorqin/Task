export const getters = {
    list: state => state.list,
    inventory: state => state.inventory,
    columns: state => state.columns,
    filter: state => state.filter,
    pagination: state => state.pagination,
    sort: state => state.sort,
    model: state => state.model,
    rules: state => state.rules,
    types: state => state.types,
    client_contact_persons: state => state.client_contact_persons,
    client_checking_accounts: state => state.client_checking_accounts,
    sales: state => state.sales,
    sale_ready_products: state => state.sale_ready_products,
    form: state => {
        return {
            id: state.model.id,
            name: state.model.name,
            full_name: state.model.full_name,
            sku: state.model.sku,
            phone: state.model.phone,
            fax: state.model.fax,
            email: state.model.email,
            object_name: state.model.object_name,
            district_id: state.model.district ? state.model.district.id : null,
            quarter_id: state.model.quarter ? state.model.quarter.id : null,
            object_street: state.model.object_street,
            object_home: state.model.object_home,
            object_corps: state.model.object_corps,
            object_flat: state.model.object_flat,
            comment: state.model.comment,
            actual_address: state.model.actual_address,
            type_id: (state.model.type) ? state.model.type.id : '',
            legal_address: state.model.legal_address,
            inn: state.model.inn,
            mfo: state.model.mfo,
            okonx: state.model.okonx,
            oked: state.model.oked,
            rkp_nds: state.model.rkp_nds
        }
    }
};
