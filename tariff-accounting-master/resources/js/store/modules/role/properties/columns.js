export const columns = {
    id : {
        show: true,
        title: '№',
        sortable: true,
        column: 'id'
    },
    name : {
        show: true,
        title: 'Наименование',
        sortable: true,
        column: 'name'
    },
    slug: {
      show: true,
      title: 'Слизень',
      sortable: true,
      column: 'slug'
    },
    created_at : {
        show: true,
        title: 'Создан',
        sortable: true,
        column: 'created_at'
    },
    updated_at : {
        show: true,
        title: 'Изменено',
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
