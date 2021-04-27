import { i18n } from '@/utils/modules/i18n';

export const rules = {
    name: [
        { required: true, message: i18n.t('message.This field is required'), trigger: 'blur' },
    ],
    price: [
        { required: true, message: i18n.t('message.This field is required'), trigger: 'blur' }
    ],
    measurement_id: [
        { required: true, message: i18n.t('message.This field is required'), trigger: 'blur' },
    ],
};
