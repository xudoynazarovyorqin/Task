export const columns = {
    id: {
        show: true,
        title: "№",
        sortable: true,
        column: 'id'
    },
    user_id: {
        show: true,
        title: "Ползаватель",
        sortable: true,
        column: 'user_id'
    },
    ip_address: {
        show: true,
        title: "IP-адрес",
        sortable: true,
        column: 'ip_address'
    },
    status: {
        show: true,
        title: "Статус",
        sortable: true,
        column: 'status'
    },
    created_at: {
        show: true,
        title: "Дата создания",
        sortable: true,
        column: 'created_at'
    },
    updated_at: {
        show: false,
        title: "Изменено",
        sortable: true,
        column: 'updated_at'
    },
    settings: {
        show: true,
        title: "Настройки",
        sortable: false,
        column: 'settings'
    }
};
