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
    provider_contact_persons: state => state.provider_contact_persons,
    provider_checking_accounts: state => state.provider_checking_accounts,
    form: state => {
        return {
            id: state.model.id,
            name: state.model.name,
            full_name: state.model.full_name,
            sku: state.model.sku,
            phone: state.model.phone,
            fax: state.model.fax,
            email: state.model.email,
            comment: state.model.comment,
            actual_address: state.model.actual_address,
            type_id: state.model.type ? model.type.id : '',
            legal_address: state.model.legal_address,
            inn: state.model.inn,
            mfo: state.model.mfo,
            okonx: state.model.okonx,
            oked: state.model.oked,
            rkp_nds: state.model.rkp_nds
        }
    }
};