import PaymentTypes from '@selects/crm-payment-type';
import currencies from '@inventory/crm-currency-select';
import amount from '@inputs/crm-amount-input';
import providers from '@selects/crm-provider';
import scores from '@selects/crm-score';
import contracts from '@selects/crm-contract-provider';
import buys from '@/pages/transaction/components/include/buys';
import { mapGetters, mapActions } from 'vuex';

export default {
    components: { PaymentTypes, currencies, amount, contracts, providers, buys, scores },
    data() {
        return {
            relatedItems: [],
            includeDrawer: false
        }
    },
    computed: {
        ...mapGetters({
            rules: 'transactions/rules',
            columns: 'transactions/columns',
            getForm: 'transactions/form',
            belongOrders: 'transactions/relatedItems',
            currencies: 'currencies/inventory',
        }),
        relatedAmount: function() {
            return _.sumBy(this.relatedItems, 'paying_amount')
        },
        realAmount: function() {
            const rate = this.form.currency ? this.form.currency.reverse ? _.round(1 / +this.form.rate, 8) : +this.form.rate : +this.form.rate;
            return this.form.amount * rate;
        }
    },
    watch: {
        realAmount: {
            handler: function() {
                this.reInstallItemsPaidAmounts();
            },
            immediate: true,
            deep: true,
        }
    },
    methods: {
        ...mapActions({
            updateProviderInventory: 'providers/inventory'
        }),
        submit(close = true) {
            if (!this.waiting) {
                this.form['relatedItems'] = _.map(_.filter(this.relatedItems, function(o) {
                    return (o.paying_amount > 0 && o.paymentable_type && o.paymentable_id)
                }), function(o) {
                    return {
                        paymentable_type: o.paymentable_type,
                        paymentable_id: o.paymentable_id,
                        paying_amount: o.paying_amount
                    }
                })
                this.form.rate = this.form.currency ? this.form.currency.reverse ? _.round(1 / +this.form.rate, 8) : +this.form.rate : +this.form.rate;
                this.$refs['form'].validate((valid) => {
                    if (valid && this.validItems()) {
                        this.waiting = true;
                        this.save(this.form)
                            .then(res => {
                                this.$alert(res);
                                this.updateProviderInventory();
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
        validItems() {
            return !_.some(this.relatedItems, function(element) {
                return (element.total_amount < element.paid_amount + element.paying_amount);
            })
        },
        updateCurrency() {
            const currency = _.find(this.currencies, { 'id': this.form.currency_id });
            if (currency) {
                this.form.currency = currency;
                this.form.rate = currency.reverse ? currency.reversed_rate : currency.rate;
            }
        },
        closeDrawer(drawer) {
            if (this.$refs[drawer] && _.isFunction(this.$refs[drawer].closeDrawer)) {
                this.$refs[drawer].closeDrawer();
            }
        },
        async drawerClosed(ref) {
            if (this.$refs[ref] && _.isFunction(this.$refs[ref].closed)) {
                this.$refs[ref].closed()
            }
            this.relatedItems = [];
            if (_.isFunction(this.setPaymentsToRelatedItems)) {
                await this.setPaymentsToRelatedItems();
            }
            this.belongOrders.forEach(element => {
                element.paying_amount = 0;
                this.relatedItems.push(JSON.parse(JSON.stringify(element)));
            });
            this.reInstallItemsPaidAmounts()
        },
        drawerOpened(ref) {
            if (this.$refs[ref] && _.isFunction(this.$refs[ref].opened)) {
                this.$refs[ref].opened()
            }
        },
        deleteRow(index, rows) {
            this.$confirm(this.$t('message.delete_confirm'), this.$t('message.confirm'), {
                confirmButtonText: this.$t('message.yes'),
                cancelButtonText: this.$t('message.cancel'),
                type: 'warning'
            }).then(() => {
                rows.splice(index, 1);
            }).catch(() => {});
        },
        reInstallItemsPaidAmounts() {
            let rem = this.realAmount;
            this.relatedItems.forEach(element => {
                let pay = 0;
                if (rem > 0) {
                    pay = (rem > (element.total_amount - element.paid_amount)) ? element.total_amount - element.paid_amount : rem;
                    element.paying_amount = pay;
                    rem -= pay;
                } else {
                    element.paying_amount = 0;
                }
            });
        },
        tableRowClassName({ row, rowIndex }) {
            if (row.total_amount < (row.paid_amount + row.paying_amount)) {
                return 'warning-row';
            }
            return '';
        }
    }
}