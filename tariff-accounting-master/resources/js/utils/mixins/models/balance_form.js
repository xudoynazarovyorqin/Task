import ShowPaymentableItems from '@modals/crm-show-paymentable-items-modal';
import { formatNumber } from '@/filters/index';
import { mapGetters, mapActions } from "vuex";

export default {
    props: ['drawer'],
    components: {
        "crm-show-paymentable-items-modal": ShowPaymentableItems
    },
    data() {
        return {
            form: {},
            waiting: false,
            have_object: false,
            is_automatic_check: false,
            multipleSelection: [],
            paymentable_items: [],
        }
    },
    async mounted() {
        if (this.payment_types && this.payment_types.length === 0)
            await this.loadPaymentTypes();
        if (this.currencies && this.currencies.length === 0)
            await this.loadCurrencies();
    },
    watch: {
        'form.currency_id': {
            handler: function(new_value, old_value) {
                let currency = this.currencies.filter((currencies) => { return this.form.currency_id === currencies.id });
                if (currency.length && currency[0].rate != null) {
                    this.form.rate = parseFloat(currency[0].rate);
                } else {
                    this.form.rate = 1;
                }
            },
            deep: true
        },

        form: {
            handler: function(new_value, old_value) {
                this.multipleSelection = [];
                if (this.have_object) {
                    this.$refs.multipleTable.clearSelection();
                }

                if (this.is_automatic_check == true) {
                    this.paymentables.forEach(paymentable => {
                        if ((this.remainder > 0) && ((this.remainder - paymentable.not_paid_price) >= 0)) {
                            this.multipleSelection.push({
                                'id': paymentable.id,
                                'payment_price': paymentable.not_paid_price,
                            });
                            this.$refs.multipleTable.toggleRowSelection(paymentable);
                        } else if ((this.remainder > 0) && ((this.remainder - paymentable.not_paid_price) < 0)) {
                            this.multipleSelection.push({
                                'id': paymentable.id,
                                'payment_price': this.remainder,
                            });

                            this.$refs.multipleTable.toggleRowSelection(paymentable);
                        }
                    });
                }
            },
            deep: true
        },

        'form.paymentable_type': {
            handler: function(new_value, old_value) {
                this.have_object = false;
                this.multipleSelection = [];
            }
        },

        is_automatic_check: {
            handler: function(new_value, old_value) {
                this.multipleSelection = [];
                this.$refs.multipleTable.clearSelection();
                if (new_value == true) {
                    this.paymentables.forEach(paymentable => {
                        if ((this.remainder > 0) && ((this.remainder - paymentable.not_paid_price) >= 0)) {
                            //this.multipleSelection.push(paymentable)
                            this.multipleSelection.push({
                                'id': paymentable.id,
                                'payment_price': paymentable.not_paid_price,
                            });

                            this.$refs.multipleTable.toggleRowSelection(paymentable);
                        } else if ((this.remainder > 0) && ((this.remainder - paymentable.not_paid_price) < 0)) {
                            this.multipleSelection.push({
                                'id': paymentable.id,
                                'payment_price': this.remainder,
                            });

                            this.$refs.multipleTable.toggleRowSelection(paymentable);
                        }
                    });
                }
            }
        },

        multipleSelection: {
            handler: function(new_value, old_value) {
                for (let key in this.paymentables) {
                    if (this.paymentables.hasOwnProperty(key)) {
                        var index = this.multipleSelection.findIndex(item => item.id === this.paymentables[key].id)
                        if (index != -1) {
                            this.paymentables[key]['payment_price_format'] = formatNumber(this.multipleSelection[index].payment_price);
                        } else {
                            this.paymentables[key]['payment_price_format'] = 0;
                        }
                    }
                }
            },
            deep: true
        },
    },
    computed: {
        ...mapGetters({
            model: "balanceHistories/model",
            rules: "balanceHistories/rules",
            columns: "balanceHistories/columns",
            paymentables_object: "balanceHistories/paymentables",
            payment_types_with_from_balance: "paymentTypes/list",
            currencies: "currencies/list",
            money: 'money',
        }),
        payment_types: function() {
            return this.payment_types_with_from_balance.filter((item) => { return item.key != 'from_balance' })
        },
        total_amount: function() {
            return parseFloat(this.form.amount) * parseFloat(this.form.rate);
        },
        remainder: function() {
            return (this.total_amount - this.multipleSelection.reduce((a, b) => a + (b.payment_price || 0), 0))
        },
        total_not_paid_price: function() {
            if (this.have_object) {
                return this.paymentables.reduce((a, b) => a + (b.not_paid_price || 0), 0);
            } else {
                return 0;
            }
        },
        paymentables: function() {
            return JSON.parse(JSON.stringify(this.paymentables_object));
        },
    },
    methods: {
        ...mapActions({
            save: "balanceHistories/store",
            getPaymentables: "balanceHistories/getPaymentables",
            loadPaymentTypes: "paymentTypes/index",
            loadCurrencies: "currencies/index",
        }),

        submit(close = true) {
            /**
             * Set paymentable_ids
             */
            if (this.have_object) {
                this.form["paymentable_ids"] = this.multipleSelection;
            }

            this.$refs["formBalance"].validate(valid => {
                if (valid && this.validateRemainderSum()) {
                    this.waiting = true;
                    this.save(this.form)
                        .then(res => {
                            this.$alert(res);
                            this.waitingStop();
                            if (close) {
                                this.close()
                            }
                            this.parent().fetchData()
                        })
                        .catch(err => {
                            this.waitingStop()
                            this.$alert(err);
                        });
                }
            });
        },

        getNotPaidObjects() {
            if (this.validatePaymentables()) {
                this.getPaymentables({
                        paymentable_type: this.form.paymentable_type,
                        balance_historyable_id: this.form.balance_historyable_id
                    })
                    .then(res => {
                        this.have_object = true;
                    })
                    .catch(err => {
                        this.$alert(err);
                        this.have_object = false;
                    });
            }
        },

        validatePaymentables() {
            if (!this.form.balance_historyable_id) {
                this.$message({
                    message: "Контрагент пустой",
                    type: "warning"
                });
                return false;
            }

            if (!this.form.paymentable_type) {
                this.$message({
                    message: "Тип проекта пустой",
                    type: "warning"
                });
                return false;
            }

            return true;
        },

        validateRemainderSum() {
            if (this.remainder < 0) {
                this.$message({
                    message: "Отрицательный остаток",
                    type: "warning"
                });
                return false;
            }

            return true;
        },
        waitingStop() {
            setTimeout(() => {
                this.waiting = false
            }, 500);
        },
        resetForm(formName) {
            this.$refs[formName].resetFields();
        },
        showModalPaymentableItems(paymentable) {
            this.paymentable_items = paymentable.paymentable_items;
            $("#show_paymentable_items").modal("show");
        }
    }
}