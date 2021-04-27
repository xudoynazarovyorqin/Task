export const rules = {
    provider_id: [
        { required: true, message: 'Пожалуйста, выберите поставщика', trigger: 'change' },
    ],
    status_id: [
        { required: true, message: 'Пожалуйста, выберите статус', trigger: 'change' }
    ],
    object_type: [
        { required: true, message: 'Пожалуйста, выберите объект', trigger: 'change' }
    ],
    object_id: [
        { required: true, message: 'Пожалуйста, заполните поля ID Объекта', trigger: 'blur' }
    ],
};