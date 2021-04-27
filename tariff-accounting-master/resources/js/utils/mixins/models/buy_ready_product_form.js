import products from '@selects/crm-product';
import providers from '@selects/crm-provider';
import contracts from '@selects/crm-contract-provider';
import statues from '@selects/crm-state';
import currencies from '@inventory/crm-currency-select';
import ProductPrice from '@inputs/crm-product-price-input';
import { mapGetters, mapActions } from "vuex";

export default {
    components: { providers, products, contracts, statues, currencies, ProductPrice },
    data() {
        return {
            form: {},
            waiting: false,
            items: [],
        }
    },
    computed: {
        ...mapGetters({
            model: 'buyReadyProducts/model',
            getForm: 'buyReadyProducts/form',
            lastId: 'buyReadyProducts/lastId',
            old_items: 'buyReadyProducts/buy_products',
            buy_product: 'buyReadyProducts/buy_product',
            columns: 'buyReadyProducts/columns',
            rules: 'buyReadyProducts/rules',
            currencies: 'currencies/inventory',
        }),
        totalAmount: function() {
            return _.sumBy([...this.old_items, ...this.items], function(o) {
                let rate = o.currency ? (o.currency.reverse ? _.round(1 / o.rate, 8) : o.rate) : 1;
                return _.round((+o.price * +o.qty_weight * +rate), 2);
            })
        },
        amounts: function() {
            return _.map(_.values(_.groupBy([...this.old_items, ...this.items], 'currency.id')), function(item) {
                return { currency: _.head(item).currency, amount: _.sumBy(item, function(o) { return (+o.price * +o.qty_weight) }) }
            });
        }
    },
    methods: {
        ...mapActions({
            empty: "buyReadyProducts/empty",
        }),
        submit() {
            this.form['items'] = _.map(this.items, function(item) {
                let rate = item.currency ? (item.currency.reverse ? _.round(1 / item.rate, 8) : item.rate) : 1;
                return { product_id: item.product.id, qty_weight: item.qty_weight, currency_id: item.currency_id, rate: rate, price: item.price };
            });
            this.$refs['form'].validate((valid) => {
                if (valid && this.validateProducts()) {
                    this.waiting = true;
                    this.save(this.form)
                        .then(res => {
                            this.$alert(res);
                            this.listChanged();
                            this.waitingStop();
                            if (close) {
                                this.close()
                            }
                        })
                        .catch(err => {
                            this.waitingStop();
                            this.$alert(err);
                        });
                }
            });
        },
        appendProduct(item, qty = 1) {
            if (item) {
                let buy_product = JSON.parse(JSON.stringify(this.buy_product));
                buy_product.product = item;
                buy_product.qty_weight = qty;
                buy_product.price = item.purchase_price;
                const currency = item.purchase_currency ? item.purchase_currency : _.find(this.currencies, 'active');
                if (currency) {
                    buy_product.currency = currency;
                    buy_product.rate = currency.reverse ? currency.reversed_rate : currency.rate;
                    buy_product.currency_id = currency.id;
                }
                this.items.push(buy_product);
            }
        },
        updateCurrency(item) {
            if (item) {
                const currency = _.find(this.currencies, ['id', item.currency_id]);
                if (currency) {
                    item.currency = currency;
                    item.rate = currency.reverse ? currency.reversed_rate : currency.rate;
                }
            }
        },
        removeProduct(line) {
            if (this.items.length > 0) this.items.splice(this.items.indexOf(line), 1)
        },
        validateProducts() {
            if (_.find(this.items, function(o) { return (o.qty_weight <= 0) })) {
                this.$message({
                    message: this.$t('message.product_validation_error'),
                    type: 'warning'
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
        listChanged() {
            this.parent().listChanged()
        }
    }
}