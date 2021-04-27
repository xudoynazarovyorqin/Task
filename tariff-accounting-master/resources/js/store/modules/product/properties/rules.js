export const rules = {
    name: [
        { required: true, message: 'Пожалуйста, введите название ', trigger: 'blur' },
    ],
    purchase_price: [
        { required: true, message: 'Пожалуйста, введите закупочная цена', trigger: 'blur' }
    ],
    selling_price: [
        { required: true, message: 'Пожалуйста, введите цена продажи', trigger: 'blur' }
    ],
    production_type: [
        { required: true, message: 'Пожалуйста, выберите тип продукта', trigger: 'change' }
    ]
};