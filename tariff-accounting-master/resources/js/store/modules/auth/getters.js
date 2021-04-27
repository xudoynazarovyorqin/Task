export const getters = {
    token: state => state.token,
    phone: state => state.phone,
    name: state => state.name,
    role: state => state.role,
    permissions: state => { return (state.role) ? state.role.permissions : [] },
    expires_in: state => state.expires_in
};