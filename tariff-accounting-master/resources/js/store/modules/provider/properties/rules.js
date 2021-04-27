import { i18n } from '@/utils/modules/i18n';

export const rules = {
    name: [
        { required: true, message: i18n.t('message.This field is required'), trigger: 'blur' }
    ],
    sku: [
        { required: true, message: i18n.t('message.This field is required'), trigger: 'blur' }
    ],
    email: [
        { required: true, message: i18n.t('message.This field is required'), trigger: 'blur' },
        { type: 'email', message: i18n.t('message.The email field must be a valid email address'), trigger: ['blur', 'change'] }
    ]
};