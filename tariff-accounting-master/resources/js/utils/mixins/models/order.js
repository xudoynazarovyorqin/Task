import CrmAssemblyReport from '@/includes/report/crm-assembly-report';
import CrmSaleReport from '@/includes/report/crm-sale-report';
import CrmEmployee from '@/includes/crm-employee';
import clients from '@selects/crm-client';
import contracts from '@selects/crm-contract-client';
import states from '@selects/crm-state';
import products from '@selects/crm-product';
import materials from '@selects/crm-material';
import priorities from '@selects/crm-priority';
import costs from '@selects/crm-cost';
import currencies from '@inventory/crm-currency-select';
import ProductPrice from '@inputs/crm-product-price-input';
import CostPrice from '@inputs/crm-cost-price-input';
import { mapGetters, mapActions } from "vuex";

export default {
    components: {
        CrmAssemblyReport,
        CrmSaleReport,
        CrmEmployee,
        clients,
        states,
        contracts,
        products,
        materials,
        priorities,
        costs,
        ProductPrice,
        CostPrice,
        currencies
    },
    data() {
        return {
            employees: [],
            activeTab: "main",
            reportChanged: false,
            order_products: [],
            order_costs: [],
            reportLoaded: false,
            additional_materials: [],
            defaultEmployeeProps: {
                children: "children",
                label: "name"
            }
        }
    },
    computed: {
        ...mapGetters({
            columns: "orders/columns",
            lastId: 'orders/lastId',
            model: "orders/model",
            order_product: "orders/order_product",
            rules: "orders/rules",
            money_product: 'money_product',
            getForm: 'orders/form',
            currencies: 'currencies/inventory',
            old_order_products: 'orders/order_products',
            old_order_costs: 'orders/order_costs',
            old_additional_materials: 'orders/additional_materials',
            oldEmployeeGroups: 'orders/employeeGroups',
        }),
        totalAmount: function() {
            return _.sumBy([...this.old_order_products, ...this.order_products], function(o) {
                let rate = o.currency ? (o.currency.reverse ? _.round(1 / o.rate, 8) : o.rate) : 1;
                return _.round((+o.quantity * +o.price * +rate), 2);
            })
        }
    },
    mounted() {
        if (!_.size(this.dataEmployees)) this.loadEmployeeGroups();
    },
    watch: {
        'form.production_type': {
            handler: function() {
                this.reportChanged = true;
                this.order_products = [];
            },
            deep: true
        },
        order_products: {
            handler: function() {
                this.reportChanged = true;
            },
            deep: true,
        },
        order_costs: {
            handler: function() {
                this.reportChanged = true;
            },
            deep: true
        },
        additional_materials: {
            handler: function() {
                this.reportChanged = true;
            },
            deep: true
        }
    },
    methods: {
        ...mapActions({
            checkDate: "orders/checkDate",
            loadEmployeeGroups: "employeeGroups/index",
        }),
        submit(close = true) {
            /**
             * Get employees
             */
            this.form["employees"] = this.employees;
            /**
             * Get order products
             */
            this.form["order_products"] = this.order_products.map((item) => {
                let rate = item.currency ? (item.currency.reverse ? _.round(1 / item.rate, 8) : item.rate) : 1;
                return { product_id: item.product.id, quantity: item.quantity, price: item.price, currency_id: item.currency_id, rate: rate };
            });
            /**
             * Get additional materials
             */
            this.form["additional_materials"] = this.additional_materials.map((item) => {
                return { material_id: item.material.id, quantity: item.quantity }
            });
            /**
             * Get order costs
             */
            this.form["order_costs"] = this.order_costs.map((item) => {
                let rate = item.currency ? (item.currency.reverse ? _.round(1 / item.rate, 8) : item.rate) : 1;
                return { cost_id: item.cost.id, amount: item.amount, currency_id: item.currency_id, rate: rate }
            });

            this.$refs["form"].validate(valid => {
                if (valid && this.customValidation()) {
                    this.waiting = true;
                    this.save(this.form)
                        .then(res => {
                            this.$alert(res);
                            this.listChanged();
                            this.waitingStop();
                            if (close) {
                                this.close()
                            } else {
                                if (this.is_new === false) this.load();
                            }
                        })
                        .catch(err => {
                            this.waitingStop()
                            this.$alert(err);
                        });
                }
            });
        },
        changeEmployee(employees) {
            this.employees = employees;
        },
        handleTabClick(tab, event) {
            switch (tab.name) {
                case 'crm-report-show':
                    if (!this.reportLoaded) {
                        if (this.form.production_type == 'production') {
                            this.$refs['crm-sale-show-report'].loadData()
                        }
                        if (this.form.production_type == 'assembly') {
                            this.$refs['crm-assembly-show-report'].loadData()
                        }
                        this.reportLoaded = true;
                    }
                    break;
                case 'crm-report':
                    if (this.reportChanged) {
                        if (this.form.production_type == 'production') {
                            this.$refs['crm-sale-report'].loadData()
                        }
                        if (this.form.production_type == 'assembly') {
                            this.$refs['crm-assembly-report'].loadData()
                        }
                        this.reportChanged = false;
                    }
                    break;
                default:
                    break;
            }
        },
        appendProduct(product) {
            let order_product = JSON.parse(JSON.stringify(this.order_product));
            order_product.product = product;
            order_product.price = product.selling_price;
            order_product.quantity = 1;
            const currency = product.selling_currency ? product.selling_currency : _.find(this.currencies, 'active');
            if (currency) {
                order_product.currency = currency;
                order_product.rate = currency.reverse ? currency.reversed_rate : currency.rate;
                order_product.currency_id = currency.id;
            }
            this.order_products.push(order_product);
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
            if (this.order_products.length > 0) this.order_products.splice(this.order_products.indexOf(line), 1);
        },
        appendAdditionalMaterial(material) {
            let additional_material = {};
            additional_material.quantity = 0;
            additional_material.material = material;
            this.additional_materials.push(additional_material);
        },
        removeAdditionalMaterial(line) {
            if (this.additional_materials.length > 0) this.additional_materials.splice(this.additional_materials.indexOf(line), 1);
        },
        appendCost(cost) {
            let order_cost = {};
            order_cost.cost = cost;
            order_cost.amount = cost.amount;
            const currency = cost.currency ? cost.currency : _.find(this.currencies, 'active');
            if (currency) {
                order_cost.currency = currency;
                order_cost.rate = currency.reverse ? currency.reversed_rate : currency.rate;
                order_cost.currency_id = currency.id;
            }
            this.order_costs.push(order_cost);
        },
        removeCost(line) {
            if (this.order_costs.length > 0) this.order_costs.splice(this.order_costs.indexOf(line), 1);
        },
        customValidation() {
            if (_.find(this.order_products, function(o) { return (o.quantity <= 0) })) {
                this.$message({
                    message: this.$t('message.product_validation_error'),
                    type: 'warning'
                });
                return false;
            }
            if (_.find(this.additional_materials, function(o) { return (o.quantity <= 0) })) {
                this.$message({
                    message: this.$t('message.material_validation_error'),
                    type: 'warning'
                });
                return false;
            }
            if (_.find(this.order_costs, function(o) { return (o.amount <= 0) })) {
                this.$message({
                    message: this.$t('message.cost_validation_error'),
                    type: 'warning'
                });
                return false;
            }
            return true;
        },
        dateIsEmpty(data) {
            if (data.begin_date && data.end_date) {
                this.checkDate(data).catch(err => {
                    this.$confirm(this.$t('message.are_you_sure'), this.$t('message.confirm'), {
                        confirmButtonText: this.$t('message.yes'),
                        cancelButtonText: this.$t('message.cancel'),
                        type: 'warning'
                    }).then(() => {}).catch(() => {});
                });
            }
        }
    },
};