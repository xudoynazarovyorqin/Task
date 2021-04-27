export const columns = {
    id: {
        show: true,
        title: "№",
        sortable: true,
        column: 'id'
    },
    name: {
        show: true,
        title: "Краткое наименование",
        sortable: true,
        column: 'name'
    },
    full_name: {
        show: true,
        title: "Полное наименование",
        sortable: true,
        column: 'full_name'
    },
    code: {
        show: true,
        title: "Цифровой код",
        sortable: true,
        column: 'code'
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
