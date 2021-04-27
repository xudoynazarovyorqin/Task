import { i18n } from '@/utils/modules/i18n';

export const rules = {
    datetime: [
        { required: true, message: i18n.t('message.This field is required'), trigger: 'change' }
    ],
};