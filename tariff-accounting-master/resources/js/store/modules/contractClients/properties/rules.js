import { i18n } from '@/utils/modules/i18n';

export const rules = {
    number: [
        { required: true, message: i18n.t('message.This field is required'), trigger: 'blur' }
    ],
    begin_date: [
        { required: true, message: i18n.t('message.This field is required'), trigger: 'blur' }
    ],
    // conclusion_date: [
    //     { required: true, message: i18n.t('message.This field is required'), trigger: 'blur' }
    // ],
    // termination_date: [
    //     { required: true, message: i18n.t('message.This field is required'), trigger: 'blur' }
    // ],
    client_id: [
        { required: true, message: i18n.t('message.This field is required'), trigger: 'blur' }
    ]
};
