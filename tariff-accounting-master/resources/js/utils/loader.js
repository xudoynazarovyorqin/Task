import Vue from 'vue'
import moment from 'moment';

/**
 * vue modal component
 */

import VModal from 'vue-js-modal'
Vue.use(VModal);
/**
 * end vue modal components
 */
/**

/*
    Progress Bar
*/
import VueProgressBar from 'vue-progressbar'
const options = {
    color: 'green',
    failedColor: 'orange',
    thickness: '5px',
    transition: {
        speed: '0.6s',
        opacity: '0.8s',
        termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
};
Vue.use(VueProgressBar, options);
/**
 * end progress bar
 */

/**
 * Element UI
 */
import ElementUI from 'element-ui';
import locale from 'element-ui/lib/locale/lang/ru-RU'
Vue.use(ElementUI, { locale });
/**
 * end element ui
 */

/**
 * For alert
 */
import { notify } from './index'
Vue.prototype.$alert = notify;

/**
 * For export excel
 */
import excel from 'vue-excel-export'
Vue.use(excel);

/**
 * For multi languages
 */
import VueI18n from 'vue-i18n'
Vue.use(VueI18n);

/**
 * money format
 */
import money from 'v-money'
import { mapGetters } from 'vuex';
Vue.use(money, { precision: 2, decimal: ',', thousands: ' ', masked: false, prefix: '', suffix: '' });

/**
 * Gloabl mixin
 */
Vue.mixin({
    data() {
        return {
            date_format: 'yyyy-MM-dd',
            date_time_format: 'yyyy-MM-dd HH:mm',
        }
    },
    computed: {
        ...mapGetters({
            auth_name: 'name',
        }),
    },
    methods: {
        print(id) {
            var mywindow = window.open('', 'PRINT', 'left=0,top=0,height=800,width=800');

            mywindow.document.write('<html><head><title>' + document.title + '</title>');
            mywindow.document.write('</head><body >');
            mywindow.document.write(document.getElementById(id).innerHTML);
            mywindow.document.write('</body></html>');

            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/

            mywindow.print();
            mywindow.close();

            return true;
        }
    },
});