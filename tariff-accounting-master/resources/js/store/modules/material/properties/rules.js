export const rules = {
    name: [
        { required: true, message: 'Пожалуйста, введите название ', trigger: 'change' },
    ],
    measurement_id: [
        { required: true, message: 'Пожалуйста, выберите ед. изм.', trigger: 'change' },
    ],
    price: [
        { required: true, message: 'Пожалуйста, введите минимальная цена', trigger: 'blur' }
    ],
};