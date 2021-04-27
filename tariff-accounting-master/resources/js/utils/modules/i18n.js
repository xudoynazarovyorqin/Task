import { en_messages } from '@/lang/en/messages';
import { ru_messages } from '@/lang/ru/messages';
import { uz_messages } from '@/lang/uz/messages';
import Vue from 'vue';
import VueI18n from 'vue-i18n';

Vue.use(VueI18n);

export const i18n = new VueI18n({
    locale: 'ru',
    fallbackLocale: 'ru',
    messages: {
        en: {
            message: en_messages
        },
        ru: {
            message: ru_messages
        },
        uz: {
            message: uz_messages
        }
    }
});