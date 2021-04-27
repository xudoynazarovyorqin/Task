import { mapGetters, mapActions } from 'vuex';

export default {
    data() {
        return {
            form: {}
        }
    },
    mounted() {
        if (!_.size(this.currencies)) this.updateCurrencyInventory();
    },
    watch: {
        'form.reversed_rate': {
            handler: function() {
                if (this.form.reverse) {
                    this.form.rate = _.round(_.divide(1, this.form.reversed_rate), 8)
                }
            }
        },
        'form.rate': {
            handler: function() {
                if (!this.form.reverse) {
                    this.form.reversed_rate = _.round(_.divide(1, this.form.rate), 8);
                }
            }
        }
    },
    computed: {
        ...mapGetters({
            model: 'currencies/model',
            rules: 'currencies/rules',
            columns: 'currencies/columns',
            getForm: 'currencies/form',
            currencies: 'currencies/inventory'
        }),
        getLabel: function() {
            let str = '';
            if (this.form.reverse) {
                let active_currency = _.find(this.currencies, 'active');
                if (active_currency) {
                    str = active_currency.symbol;
                }
            } else {
                str = this.form.symbol;
            }
            return ('1 ' + str + ' =');
        },
        getAppendText: function() {
            let str = '';
            if (!this.form.reverse) {
                let active_currency = _.find(this.currencies, 'active');
                if (active_currency) {
                    str = active_currency.symbol;
                }
            } else {
                str = this.form.symbol;
            }
            return str;
        }
    },
    methods: {
        ...mapActions({
            updateCurrencyInventory: 'currencies/inventory'
        }),
        submit(close = true) {
            this.$refs['form'].validate((valid) => {
                if (valid) {
                    this.waiting = true;
                    this.save(this.form)
                        .then(res => {
                            this.updateCurrencyInventory();
                            this.$alert(res);
                            this.listChanged();
                            this.waitingStop();
                            this.close();
                        })
                        .catch(err => {
                            this.waitingStop();
                            this.$alert(err);
                        });
                }
            });
        },

    }
}