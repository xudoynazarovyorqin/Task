export const columns = {
    id: {
        show: true,
        title: "№",
        sortable: true,
        column: 'id'
    },
    active: {
        show: true,
        title: "Валюта учета",
        sortable: false,
        column: 'active'
    },
    reverse: {
        show: false,
        title: "Обратный курс",
        sortable: false,
        column: 'reverse',
        changeable: false
    },
    symbol: {
        show: true,
        title: "Наименование",
        sortable: true,
        column: 'symbol'
    },
    name: {
        show: true,
        title: "Полное наименование",
        sortable: true,
        column: 'name'
    },
    rate: {
        show: true,
        title: "Курс",
        sortable: true,
        column: 'rate'
    },
    created_at: {
        show: false,
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