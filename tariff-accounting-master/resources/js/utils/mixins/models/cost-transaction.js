import PaymentTypes from '@selects/crm-payment-type';
import currencies from '@inventory/crm-currency-select';
import amount from '@inputs/crm-amount-input';
import scores from '@selects/crm-score';
import { mapGetters, mapActions } from 'vuex';

export default {
    components: { PaymentTypes, currencies, amount, scores },
    data() {
        return {
            relatedItems: [],
            includeDrawer: false
        }
    },
    computed: {
        ...mapGetters({
            rules: 'transactions/rules',
            columns: 'costTransactions/columns',
            getForm: 'transactions/form',
            costs: 'costs/inventory',
            currencies: 'currencies/inventory',
        })
    },
    methods: {
        submit(close = true) {
            if (!this.waiting) {
                this.form.rate = this.form.currency ? this.form.currency.reverse ? _.round(1 / +this.form.rate, 8) : +this.form.rate : +this.form.rate;
                this.$refs['form'].validate((valid) => {
                    if (valid) {
                        this.form = _.omit(this.form, 'currency');
                        this.waiting = true;
                        this.save(this.form)
                            .then(res => {
                                this.$alert(res);
                                this.listChanged();
                                this.waitingStop();
                                if (close) this.close()
                            })
                            .catch(err => {
                                this.waitingStop();
                                this.$alert(err);
                            });
                    }
                });
            }
        },
        updateCurrency() {
            const currency = _.find(this.currencies, { 'id': this.form.currency_id });
            if (currency) {
                this.form.currency = currency;
                this.form.rate = currency.reverse ? currency.reversed_rate : currency.rate;
            }
        },
   }
}