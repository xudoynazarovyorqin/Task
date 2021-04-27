import transactions from '@/pages/distributionCost/components/include/transactions';
import { mapGetters, mapActions } from 'vuex';

export default {
    components: { transactions },
    data() {
        return {
            items: [],
            type_document: '',
            drawerIncludeTransactions: false,
            loadingMaterials: false,
        }
    },
    computed: {
        ...mapGetters({
            rules: 'distributionCosts/rules',
            columns: 'distributionCosts/columns',
            getForm: 'distributionCosts/form',
            belongTransactions: 'distributionCosts/transactions',
        }),
        totalTransactionsAmount: function() {
            return _.sumBy(this.belongTransactions, 'distributioning_amount')
        },
        totalMaterialsPrice: function() {
            return _.sumBy(this.items, function(o) {
                return _.round((+o.remainder * +o.buy_price * +o.rate), 2);
            })
        }
    },    
    methods: {
        ...mapActions({
            getWarehouseMaterials: 'distributionCosts/getWarehouseMaterials',
        }),
        submit(close = true) {
            if (!this.waiting) {
                this.form['items'] = _.map(this.items, function(o) {
                    return {
                        additional_priceable_type: 'warehouse_materials',
                        additional_priceable_id: o.id,
                        price: o.additional_price,
                    }
                })

                this.form['transactions'] = _.map(this.belongTransactions, function(o) {
                    return {
                        transaction_id: o.transaction_id,                        
                        price: o.distributioning_amount,
                    }
                })

                this.$refs['form'].validate((valid) => {
                    if (valid) {
                        this.waiting = true;
                        this.save(this.form)
                            .then(res => {
                                this.$alert(res);
                                this.listChanged();
                                this.waitingStop();
                                this.$store.commit('distributionCosts/SET_TRANSACTIONS',[]);
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

        loadMaterials() {
            this.loadingMaterials = true;
            this.getWarehouseMaterials({ warehouse_materialable_type: this.type_document, from_date: this.form.from_date, to_date: this.form.to_date })
                .then(res => {
                    this.items = [];
                    this.loadingMaterials = false;
                    if (res.data && res.data.warehouse_materials) {
                        res.data.warehouse_materials.forEach(warehouse_material => {
                            warehouse_material.additional_price = 0;
                            this.items.push(warehouse_material);
                        });                        
                    }

                    this.reInstallItemsAdditionalPrice();
                })
                .catch(err => {
                    this.loadingMaterials = false;
                })
        },
        
        closeDrawer(drawer) {
            if (this.$refs[drawer] && _.isFunction(this.$refs[drawer].closeDrawer)) {
                this.$refs[drawer].closeDrawer();
            }
        },
        drawerOpened(ref) {
            if (this.$refs[ref] && _.isFunction(this.$refs[ref].opened)) {
                this.$refs[ref].opened()
            }
        },
        async drawerClosed(ref) {
            if (this.$refs[ref] && _.isFunction(this.$refs[ref].closed)) {
                this.$refs[ref].closed()
            }

            this.reInstallItemsAdditionalPrice();
        },
        deleteRow(index, rows) {
            this.$confirm(this.$t('message.delete_confirm'), this.$t('message.confirm'), {
                confirmButtonText: this.$t('message.yes'),
                cancelButtonText: this.$t('message.cancel'),
                type: 'warning'
            }).then(() => {
                rows.splice(index, 1);
                this.reInstallItemsAdditionalPrice();
            }).catch(() => {});
        },
        reInstallItemsAdditionalPrice() {
            this.items.forEach(element => {
                element.additional_price = (this.totalMaterialsPrice) ? _.round((element.buy_price * element.rate * this.totalTransactionsAmount) / (this.totalMaterialsPrice), 2) : 0;
            });
        },        
    }
}