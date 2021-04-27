import { i18n } from '@/utils/modules/i18n';
const validate = (rule, price, callback) => {
    if (price <= 0) {
        callback(new Error(i18n.t('message.The amount must be greater than zero')));
    } else {
        callback();
    }
};

export const rules = {
    transactionable_id: [
        { required: true, message: i18n.t('message.This field is required'), trigger: 'change' }
    ],
    amount: [
        { required: true, message: i18n.t('message.This field is required'), trigger: 'change' },
        { validator: validate, trigger: 'blur' }
    ],
};
