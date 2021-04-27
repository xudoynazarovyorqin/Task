export const rules = {
    name: [
        { required: true, message: 'Пожалуйста, введите название ', trigger: 'blur' },
        { min: 2, max: 200, message: 'Длина должна быть от 2 до 200', trigger: 'blur' }
    ],
    code: [
        { required: true, message: 'Пожалуйста, введите цифровой код ', trigger: 'blur' },
    ],
    full_name: [
        { required: true, message: 'Пожалуйста, введите полное наименование ', trigger: 'blur' },
    ],
};