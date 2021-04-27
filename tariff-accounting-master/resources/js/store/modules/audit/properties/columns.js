export const columns = {
    id: {
        show: true,
        title: "№",
        sortable: true,
        column: 'id'
    },
    user_id: {
        show: true,
        title: "ID Пользователя",
        sortable: true,
        column: 'user_id'
    },
    username: {
        show: true,
        title: "Пользователь",
        sortable: false,
        column: 'username'
    },
    event: {
        show: true,
        title: "Действие",
        sortable: true,
        column: 'event'
    },
    auditable_type: {
        show: true,
        title: "Объект",
        sortable: true,
        column: 'auditable_type'
    },
    auditable_id: {
        show: true,
        title: "ID Объекта",
        sortable: true,
        column: 'auditable_id'
    },
    ip_address: {
        show: true,
        title: "IP Адрес",
        sortable: true,
        column: 'ip_address'
    },
    created_at: {
        show: true,
        title: "Дата создания",
        sortable: true,
        column: 'created_at'
    },
    settings: {
        show: false,
        title: "Настройки",
        sortable: false,
        column: 'settings'
    },
    changes: {
        show: true,
        title: "Изменения",
        sortable: false,
        column: 'settings'
    }
};