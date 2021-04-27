export const columns = {
    id: {
        show: true,
        title: "№",
        sortable: true,
        column: 'id'
    },
    name: {
        show: true,
        title: "Название",
        sortable: true,
        column: 'name'
    },
    color: {
        show: true,
        title: "Цвет",
        sortable: true,
        column: 'color'
    },
    left: {
        show: true,
        title: "Предыдущий уровень",
        sortable: true,
        column: 'left'
    },
    right: {
        show: true,
        title: "Следующий уровень",
        sortable: true,
        column: 'right'
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
