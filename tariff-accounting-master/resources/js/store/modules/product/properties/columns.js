export const columns = {
    id: {
        show: true,
        title: '№',
        sortable: true,
        column: 'id'
    },
    name: {
        show: true,
        title: 'Наименование',
        sortable: true,
        column: 'name'
    },
    code: {
        show: true,
        title: 'Код',
        sortable: true,
        column: 'code'
    },
    measurement_id: {
        show: true,
        title: 'Ед. изм.',
        sortable: true,
        column: 'measurement_id'
    },
    purchase_price: {
        show: true,
        title: 'Цена',
        sortable: true,
        column: 'purchase_price'
    },
    selling_price: {
        show: true,
        title: 'Цена продажи',
        sortable: true,
        column: 'selling_price'
    },
    vendor_code: {
        show: false,
        title: 'Артикул',
        sortable: true,
        column: 'vendor_code'
    },
    recycled: {
        show: false,
        title: 'Переработанный',
        sortable: true,
        column: 'recycled'
    },
    weight: {
        show: false,
        title: 'Вес',
        sortable: true,
        column: 'weight',
        changeable: false,
    },
    country_id: {
        show: false,
        title: 'Страна',
        sortable: true,
        column: 'country_id'
    },
    warehouse_type_id: {
        show: true,
        title: 'Тип склада',
        sortable: true,
        column: 'warehouse_type_id'
    },
    production: {
        show: true,
        title: 'Производственный',
        sortable: true,
        column: 'production'
    },
    production_type: {
        show: true,
        title: 'Тип продукта',
        sortable: true,
        column: 'production_type'
    },
    nds: {
        show: false,
        title: 'НДС',
        sortable: true,
        column: 'nds'
    },
    minimum_price: {
        show: false,
        title: 'Минимальная цена',
        sortable: true,
        column: 'minimum_price',
        changeable: false
    },
    minimum_balance: {
        show: false,
        title: 'Критическое количество',
        sortable: true,
        column: 'minimum_balance',
        changeable: false,
    },
    description: {
        show: false,
        title: 'Описание',
        sortable: true,
        column: 'description'
    },
    created_at: {
        show: false,
        title: 'Дата создания',
        sortable: true,
        column: 'created_at'
    },
    updated_at: {
        show: false,
        title: 'Изменено',
        sortable: true,
        column: 'updated_at'
    },
    settings: {
        show: true,
        title: 'Настройки',
        sortable: false,
        column: 'settings'
    }
};