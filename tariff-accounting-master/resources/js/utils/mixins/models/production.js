import CrmSaleReport from '@/includes/report/crm-sale-report';
import CrmEmployee from '@/includes/crm-employee';
import states from '@selects/crm-state';
import products from '@selects/crm-product';
import materials from '@selects/crm-material';
import priorities from '@selects/crm-priority';
import levels from '@selects/crm-level';
import { formatNumber } from '@/filters';
import { mapGetters, mapActions } from 'vuex';

export default {
    components: { CrmSaleReport, CrmEmployee, states, products, materials, priorities, levels },
    data() {
        return {
            form: {},
            items: [],
            additional_materials: [],
            reportChanged: false,
            reportLoaded: false,
            activeTab: 'main',
            employees: [],
            defaultEmployeeProps: {
                children: 'children',
                label: 'name'
            }
        }
    },
    computed: {
        ...mapGetters({
            columns: 'sales/columns',
            old_items: 'sales/sale_products',
            old_additional_materials: 'sales/additional_materials',
            model: 'sales/model',
            sale_product: 'sales/sale_product',
            rules: 'sales/rules',
            dataEmployees: 'employeeGroups/list',
            lastId: 'sales/lastId',
            getForm: 'sales/form',
        })
    },
    mounted() {
        if (this.dataEmployees && this.dataEmployees.length === 0) this.loadEmployeeGroups();
    },
    watch: {
        items: {
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
            checkDate: 'orders/checkDate',
            loadEmployeeGroups: 'employeeGroups/index',
            empty: 'sales/empty',
        }),
        submit(close = true) {
            /**
             * Get sale products list
             */
            this.form['items'] = this.items.map((item) => {
                return { product_id: item.product.id, quantity: item.quantity }
            });
            /**
             * Get additional materials
             */
            this.form['additional_materials'] = this.additional_materials.map((item) => {
                return { material_id: item.material.id, quantity: item.quantity }
            });
            /**
             * Get employees
             */
            this.form['employees'] = this.employees;
            this.$refs['form'].validate(valid => {
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
                            this.waitingStop();
                            this.$alert(err);
                        });
                }
            });
        },
        customValidation() {
            if (_.find(this.items, function(o) { return (o.quantity < 0) })) {
                this.$message({
                    message: this.$t('message.product_validation_error'),
                    type: 'warning'
                });
                return false;
            }
            if (_.find(this.additional_materials, function(o) { return (o.quantity < 0) })) {
                this.$message({
                    message: this.$t('message.material_validation_error'),
                    type: 'warning'
                });
                return false;
            }
            return true;
        },
        changeEmployee(employees) {
            this.employees = employees;
        },
        appendProduct(product) {
            let item = JSON.parse(JSON.stringify(this.sale_product));
            item.product = product;
            item.quantity = 1;
            this.items.push(item);
        },
        removeProduct(line) {
            if (this.items.length > 0)
                this.items.splice(this.items.indexOf(line), 1);
        },
        appendAdditionalMaterial(material) {
            let additional_material = {};
            additional_material.quantity = 1;
            additional_material.material = material;
            this.additional_materials.push(additional_material);
        },
        removeAdditionalMaterial(line) {
            if (this.additional_materials.length > 0)
                this.additional_materials.splice(this.additional_materials.indexOf(line), 1);
        },
        handleTabClick(tab, event) {
            switch (tab.name) {
                case 'crm-report-show':
                    if (!this.reportLoaded) {
                        this.$refs['crm-sale-show-report'].loadData()
                        this.reportLoaded = true
                    }
                    break;
                case 'crm-report':
                    if (this.reportChanged) {
                        this.$refs['crm-sale-report'].loadData()
                        this.reportChanged = false
                    }
                    break;
                default:
                    break;
            }
        },
        dateIsEmpty(data) {
            if (data.begin_date && data.end_date) {
                this.checkDate(data).catch(err => {
                    this.$confirm(this.$t('message.are_you_sure'), this.$t('message.confirm_dates'), {
                        confirmButtonText: this.$t('message.yes'),
                        cancelButtonText: this.$t('message.cancel'),
                        type: 'warning'
                    }).then(() => {}).catch(() => {});
                });
            }
        },
        closeCreateStateModal(data) {
            if (data.created && data.created === true) {
                this.form.state_id = data.state.id;
            }
        },
        closeCreatePriorityModal(data) {
            if (data.created && data.created === true) {
                this.form.priority_id = data.priority.id;
            }
        },
        closeCreateLevelModal(data) {
            if (data.created && data.created === true) {
                this.form.level_id = data.level.id;
            }
        },
        afterLeave() {
            this.empty()
        },
        getProductSummeries() {
            return [
                this.$t('message.total'),
                formatNumber(_.sumBy([...this.old_items, ...this.items], function(o) { return +o.quantity })),
                '',
            ];
        },
        getMaterialSummeries() {
            return [
                this.$t('message.total'),
                formatNumber(_.sumBy([...this.old_additional_materials, ...this.additional_materials], function(o) {
                    return (+o.quantity)
                })),
                ''
            ];
        }
    },
};