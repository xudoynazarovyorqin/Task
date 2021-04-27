import { i18n } from '@/utils/modules/i18n';

export const rules = {
    realizationable_type: [
        { required: true, message: i18n.t('message.This field is required'), trigger: 'change' }
    ],
    realizationable_id: [
        { required: true, message: i18n.t('message.This field is required'), trigger: 'change' }
    ],
    datetime: [
        { required: true, message: i18n.t('message.This field is required'), trigger: 'change' }
    ],
    user_id: [
        { required: true, message: i18n.t('message.This field is required'), trigger: 'change' }
    ]
};