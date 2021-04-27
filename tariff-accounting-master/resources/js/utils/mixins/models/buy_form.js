import materials from '@selects/crm-material';
import providers from '@selects/crm-provider';
import contracts from '@selects/crm-contract-provider';
import statues from '@selects/crm-state';
import currencies from '@inventory/crm-currency-select';
import MaterialPrice from '@inputs/crm-material-price-input';
import { mapGetters, mapActions } from "vuex";

export default {
    components: {
        providers,
        contracts,
        materials,
        statues,
        currencies,
        MaterialPrice
    },
    data() {
        return {
            form: {},
            waiting: false,
            items: [],
        }
    },
    computed: {
        ...mapGetters({
            model: 'buys/model',
            getForm: 'buys/form',
            lastId: 'buys/lastId',
            buy_material: 'buys/buy_material',
            buy_materials: 'buys/buy_materials',
            old_items: 'buys/buy_materials',
            columns: 'buys/columns',
            rules: 'buys/rules',
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
            loadCurrencies: "currencies/inventory",
            empty: "buys/empty",
        }),
        submit() {
            this.form['items'] = _.map(this.items, function(item) {
                let rate = item.currency ? (item.currency.reverse ? _.round(1 / item.rate, 8) : item.rate) : 1;
                return { material_id: item.material.id, qty_weight: item.qty_weight, currency_id: item.currency_id, rate: rate, price: item.price };
            })
            this.$refs['form'].validate((valid) => {
                if (valid && this.validateMaterials()) {
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
        },
        appendMaterial(item, qty = 1) {
            if (item) {
                let buy_material = JSON.parse(JSON.stringify(this.buy_material));
                buy_material.material = item;
                buy_material.price = item.price;
                buy_material.qty_weight = qty;
                const currency = item.price_currency ? item.price_currency : _.find(this.currencies, 'active');
                if (currency) {
                    buy_material.currency = currency;
                    buy_material.rate = currency.reverse ? currency.reversed_rate : currency.rate;
                    buy_material.currency_id = currency.id;
                }
                this.items.push(buy_material);
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
        removeMaterial(line) {
            if (_.size(this.items)) this.items.splice(this.items.indexOf(line), 1)
        },
        validateMaterials() {
            if (_.find(this.items, function(o) { return (o.qty_weight <= 0) })) {
                this.$message({
                    message: this.$t('message.material_validation_error'),
                    type: 'warning'
                });
                return false;
            }
            return true;
        },
        waitingStop() {
            setTimeout(() => {
                this.waiting = false;
            }, 500);
        },
        listChanged() {
            this.parent().listChanged()
        }
    }
}