import { validation } from "@/api/users";
import { parseValidationErrorToStr } from "@/utils";

const validatePhone = (rule, phone, callback) => {
    if (phone === '') {
        callback(new Error('Пожалуйста, введите телефон'));
    } else {
        validation({ 'phone': phone })
            .then(res => {
                callback()
            })
            .catch(err => {
                if (err.response.status === 422) {
                    callback(new Error(parseValidationErrorToStr(err.response.data.validation_errors.phone)))
                }
            });
    }
};

const validatePinCode = (rule, pin_code, callback) => {
    if (pin_code === '') {
        callback()
    } else if (!Number.isInteger(pin_code)) {
        callback(new Error('Пин код должна быть числом'));
    } else {
        validation({ 'pin_code': pin_code })
            .then(res => {
                callback()
            })
            .catch(err => {
                if (err.response.status === 422) {
                    callback(new Error(parseValidationErrorToStr(err.response.data.validation_errors.pin_code)))
                }
            });
    }
};

export const rules = {
    name: [
        { required: true, message: 'Пожалуйста, введите название ', trigger: 'blur' },
        { min: 3, max: 255, message: 'Длина должна быть от 3 до 255', trigger: 'blur' }
    ],
    phone: [
        { required: true, validator: validatePhone, trigger: 'blur' }
    ],
    password: [
        { required: true, message: 'Пожалуйста, введите пароль', trigger: 'blur' },
        { min: 6, max: 255, message: 'Длина должна быть от 3 до 255', trigger: 'blur' },
    ],
    status: [
        { required: true, message: 'Пожалуйста, введите статусь', trigger: 'change' },
    ],
    pin_code: [
        { required: true, validator: validatePinCode, trigger: 'blur' }
    ],
};