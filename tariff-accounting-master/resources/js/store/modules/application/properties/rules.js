import { i18n } from '@/utils/modules/i18n';

export const rules = {
    datetime: [
        { required: true, message: i18n.t('message.This field is required'), trigger: 'change' }
    ],
    client_id: [
        { required: true, message: i18n.t('message.This field is required'), trigger: 'change' },
    ],
    contract_client_id: [
        { required: true, message: i18n.t('message.This field is required'), trigger: 'change' },
    ],
    status_id: [
        { required: true, message: i18n.t('message.This field is required'), trigger: 'change' }
    ],
    console_number: [
        { required: true, message: i18n.t('message.This field is required'), trigger: 'blur' }
    ],
};
