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
    parent_id: {
        show: true,
        title: "Группа",
        sortable: true,
        column: 'parent_id'
    },
    slug: {
        show: true,
        title: "Слизень",
        sortable: true,
        column: 'slug'
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
