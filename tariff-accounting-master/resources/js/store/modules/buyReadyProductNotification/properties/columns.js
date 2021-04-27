export const columns = {
    id: {
        show: true,
        title: "№",
        sortable: true,
        column: 'id',
    },
    buy_ready_product_notificationable_type: {
        show: true,
        title: "Тип проекта",
        sortable: true,
        column: 'buy_ready_product_notificationable_type'
    },
    buy_ready_product_notificationable_id: {
        show: false,
        title: "№ Тип проекта",
        sortable: true,
        column: 'buy_ready_product_notificationable_id'
    },
    products: {
        show: true,
        title: "Продукты",
        sortable: false,
        column: 'products'
    },
    status: {
        show: true,
        title: "Статус",
        sortable: true,
        column: 'status'
    },
    body: {
        show: true,
        title: "Причина",
        sortable: true,
        column: 'body'
    },
    created_at: {
        show: true,
        title: "Дата создания",
        sortable: true,
        column: 'created_at'
    },
    updated_at: {
        show: false,
        title: "Дата изменения",
        sortable: true,
        column: 'updated_at'
    },
    success: {
        show: true,
        title: "Принять",
        sortable: false,
        column: 'success'
    },
    cancel: {
        show: true,
        title: "Отказать",
        sortable: false,
        column: 'cancel'
    }
};