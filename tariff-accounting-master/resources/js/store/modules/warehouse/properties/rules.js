export const rules = {
    name: [
        { required: true, message: 'Пожалуйста, введите название ', trigger: 'change' },
    ],
    warehouse_type_id: [
        { required: true, message: 'Пожалуйста, выберите тип склада ', trigger: 'change' },
    ],
};
