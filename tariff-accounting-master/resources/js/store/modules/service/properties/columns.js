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
    price: {
        show: true,
        title: "Цена",
        sortable: true,
        column: 'price'
    },
    measurement_id: {
        show: true,
        title: "Ед. изм.",
        sortable: true,
        column: 'measurement_id'
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
