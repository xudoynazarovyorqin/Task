export const columns = {
    id: {
        show: true,
        title: "№",
        sortable: true,
        column: 'id'
    },
    name: {
        show: true,
        title: "Наименование",
        sortable: true,
        column: 'name'
    },
    amount: {
        show: true,
        title: "Сумма",
        sortable: true,
        column: 'amount'
    },
    description: {
        show: true,
        title: "Описание",
        sortable: true,
        column: 'description'
    },
    is_distribution: {
        show: true,
        title: "Распределенный",
        sortable: true,
        column: 'is_distribution'
    },
    created_at: {
        show: false,
        title: "Дата создания",
        sortable: true,
        column: 'created_at'
    },
    updated_at: {
        show: true,
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