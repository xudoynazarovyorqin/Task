export const columns = {
    sale_id: {
        show: false,
        title: "№ Заказь покупателей",
        sortable: false,
        column: 'sale_id'
    },
    defectable_type: {
        show: true,
        title: "Тип",
        sortable: false,
        column: 'defectable_type'
    },
    defectable_id: {
        show: true,
        title: "ID типа",
        sortable: false,
        column: 'defectable_id'
    },
    product_id: {
        show: true,
        title: "Наименование продукции",
        sortable: true,
        column: 'product_id'
    },
    quantity: {
        show: true,
        title: "Кол-о",
        sortable: false,
        column: 'quantity'
    },
    date: {
        show: false,
        title: "Дата",
        sortable: false,
        column: 'date'
    },
    created_at: {
        show: false,
        title: "Дата создания",
        sortable: false,
        column: 'created_at'
    },
};