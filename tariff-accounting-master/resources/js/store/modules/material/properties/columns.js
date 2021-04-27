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
    sku: {
        show: false,
        title: "Артикул",
        sortable: true,
        column: 'sku'
    },
    code: {
        show: true,
        title: "Код",
        sortable: true,
        column: 'code'
    },
    price: {
        show: true,
        title: "Цена",
        sortable: true,
        column: 'price'
    },
    critical_weight: {
        show: false,
        title: "Критический вес",
        sortable: true,
        column: 'critical_weight'
    },
    is_active: {
        show: true,
        title: "Активный",
        sortable: true,
        column: 'is_active'
    },
    type_id: {
        show: false,
        title: "Тип сирья",
        sortable: true,
        column: 'type_id',
        changeable: false,
    },
    measurement_id: {
        show: true,
        title: "Ед. изм.",
        sortable: true,
        column: 'measurement_id'
    },
    country_id: {
        show: false,
        title: "Страна",
        sortable: true,
        column: 'country_id'
    },
    warehouse_type_id: {
        show: true,
        title: "Тип склада",
        sortable: true,
        column: 'warehouse_type_id'
    },
    workplace_id: {
        show: true,
        title: "Рабочее место",
        sortable: true,
        column: 'workplace_id',
        changeable: false,
    },
    is_reworking: {
        show: false,
        title: "Переработанное",
        sortable: true,
        column: 'is_reworking'
    },
    measurement_changeable: {
        show: false,
        title: "Переменные ед. изм",
        sortable: false,
        column: 'measurement_changeable'
    },
    additional_measurement_id: {
        show: false,
        title: "Доп. ед изм",
        sortable: true,
        column: 'additional_measurement_id'
    },
    additional_measurement_rate: {
        show: false,
        title: "Доп. ед изм валюта",
        sortable: true,
        column: 'additional_measurement_rate'
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