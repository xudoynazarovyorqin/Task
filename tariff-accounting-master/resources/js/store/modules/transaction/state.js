import { columns } from './properties/columns'
import { filter } from './properties/filter'
import { sort } from './../properties/sort'
import { pagination } from './../properties/pagination'
import { model } from "./properties/model";
import { rules } from "./properties/rules";
import { i18n } from '@/utils/modules/i18n';

export const state = {
    list: [],
    lastId: null,
    model: JSON.parse(JSON.stringify(model)),
    columns: columns,
    filter: filter,
    pagination: JSON.parse(JSON.stringify(pagination)),
    sort: JSON.parse(JSON.stringify(sort)),
    rules: rules,
    relatedItems: [],
    payments: [],
    payment_systems: {
        payme: i18n.t('message.Payme'),
        click: i18n.t('message.Click'),
        paynet: i18n.t('message.Paynet'),
        cash: i18n.t('message.Cash'),
    },
    transaction_states: {
        '1': i18n.t('message.Created'),
        '2': i18n.t('message.Completed'),
        '-1': i18n.t('message.Canceled'),
        '-2': i18n.t('message.Canceled after complete'),
    },
    transaction_amounts: {},
};
