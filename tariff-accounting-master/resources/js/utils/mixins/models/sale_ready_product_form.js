import states from '@selects/crm-state';
import clients from '@selects/crm-client';
import contracts from '@selects/crm-contract-client';
import products from '@selects/crm-product';
import currencies from '@inventory/crm-currency-select';
import { mapGetters, mapActions } from "vuex";
import ProductPrice from '@inputs/crm-product-price-input';

export default {
    components: {
        clients,
        states,
        contracts,
        products,
        ProductPrice,
        currencies
    },
    data() {
        return {
            form: {},
            items: [],
            waiting: false,
        }
    },
    computed: {
        ...mapGetters({
            model: 'saleReadyProducts/model',
            columns: 'saleReadyProducts/columns',
            old_items: 'saleReadyProducts/items',
            sale_product: 'saleReadyProducts/sale_product',
            rules: 'saleReadyProducts/rules',
            money_product: "money_product",
            last_id: 'saleReadyProducts/last_id',
            getForm: 'saleReadyProducts/form',
            currencies: 'currencies/inventory',
        }),
        totalAmount: function() {
            return _.sumBy([...this.old_items, ...this.items], function(o) {
                let rate = o.currency ? (o.currency.reverse ? _.round(1 / o.rate, 8) : o.rate) : 1;
                return _.round((+o.selling_price * +o.quantity * +rate), 2);
            })
        }
    },
    methods: {
        ...mapActions({
            productShow: 'products/show',
            empty: "saleReadyProducts/empty",
        }),
        submit(close = true) {
            if (!this.waiting) {
                this.form['items'] = _.map(this.items, function(o) {
                    let rate = o.currency ? (o.currency.reverse ? _.round(1 / o.rate, 8) : o.rate) : 1;
                    return { product_id: o.product.id, quantity: o.quantity, selling_price: o.selling_price, currency_id: o.currency_id, rate: rate }
                });
                this.$refs['form'].validate((valid) => {
                    if (valid && this.validItems()) {
                        this.waiting = true;
                        this.save(this.form)
                            .then(res => {
                                this.$alert(res);
                                this.listChanged();
                                this.waitingStop();
                                this.close()
                            })

                        .catch(err => {
                            this.waitingStop();
                            this.$alert(err);
                        });
                    }
                });
            }
        },
        appendProduct(item) {
            if (item && !_.find(this.items, ['product.id', item.id])) {
                this.changeLoading(true);
                this.productShow({ id: item.id, params: { warehouse_info: true } })
                    .then(res => {
                        let sale_product = JSON.parse(JSON.stringify(this.sale_product));
                        sale_product.remainder = res.data ? res.data.available : 0;
                        sale_product.booked = res.data ? res.data.booked : 0;
                        sale_product.product = item;
                        sale_product.selling_price = item.selling_price;
                        const currency = item.selling_currency ? item.selling_currency : _.find(this.currencies, 'active');
                        if (currency) {
                            sale_product.currency = currency;
                            sale_product.rate = currency.reverse ? currency.reversed_rate : currency.rate;
                            sale_product.currency_id = currency.id;
                        }
                        this.items.push(sale_product);
                        this.changeLoading();
                    })
                    .catch(err => {
                        this.changeLoading();
                        this.$alert(err)
                    })
            }
        },
        validItems() {
            if (_.find(this.items, function(o) {
                    return (o.quantity > o.remainder || o.quantity == 0)
                })) {
                this.$message({
                    message: this.$t('message.product_validation_error'),
                    type: 'warning'
                });
                return false;
            }
            return true;
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
            if (_.size(this.items)) this.items.splice(this.items.indexOf(line), 1)
        },
        waitingStop() {
            setTimeout(() => {
                this.waiting = false
            }, 500);
        },
        listChanged() {
            this.parent().listChanged()
        },
        tableRowClassName({ row, rowIndex }) {
            if (row.remainder == 0) {
                return 'warning-row';
            }
            return '';
        }
    }
}