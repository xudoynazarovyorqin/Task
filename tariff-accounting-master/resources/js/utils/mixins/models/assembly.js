import CrmAssemblyReport from '@/includes/report/crm-assembly-report';
import CrmEmployee from '@/includes/crm-employee';
import states from '@selects/crm-state';
import priorities from '@selects/crm-priority';
import products from '@selects/crm-product';
import materials from '@selects/crm-material';
import { mapGetters, mapActions } from "vuex";
import { formatNumber } from '@/filters';

export default {
    components: { CrmAssemblyReport, CrmEmployee, states, priorities, products, materials },
    data() {
        return {
            assembly_items: [],
            additional_materials: [],
            reportChanged: false,
            activeTab: "main",
            employees: [],
            reportLoaded: false,
            defaultEmployeeProps: {
                children: "children",
                label: "name"
            }
        }
    },
    computed: {
        ...mapGetters({
            money_product: "money_product",
            states: "states/list",
            getForm: 'assembly/form',
            lastId: 'assembly/lastId',
            priorities: "priority/list",
            columns: "assembly/columns",
            model: "assembly/model",
            assembly_item: "assembly/assembly_item",
            rules: "assembly/rules",
            dataEmployees: "employeeGroups/list",
            old_items: 'assembly/assembly_items',
            old_additional_materials: 'assembly/additional_materials',
        }),
        checkOwner: function() {
            return this.form.owner == 'client'
        }
    },
    mounted() {
        if (this.dataEmployees && this.dataEmployees.length === 0) this.loadEmployeeGroups();
    },
    watch: {
        assembly_items: {
            handler: function() {
                this.reportChanged = true
            },
            deep: true
        },
        additional_materials: {
            handler: function(params) {
                this.reportChanged = true
            },
            deep: true
        },
    },
    methods: {
        ...mapActions({
            checkDate: "orders/checkDate",
            loadEmployeeGroups: "employeeGroups/index",
            empty: "assembly/empty",
        }),
        submit(close = true) {
            /*
             * Get employees
             */
            this.form["employees"] = this.employees;
            /**
             * Get products list
             */
            this.form["items"] = this.assembly_items.map((item) => {
                return { product_id: item.product.id, quantity: item.quantity }
            });
            /**
             * Get additional materials list
             */
            this.form["additional_materials"] = this.additional_materials.map((item) => {
                return { material_id: item.material_id, quantity: item.quantity }
            });
            this.$refs["form"].validate(valid => {
                if (valid && this.customValidation()) {
                    this.waiting = true;
                    this.save(this.form)
                        .then(res => {
                            this.$alert(res);
                            this.listChanged()
                            this.waitingStop()
                            if (close) {
                                this.close()
                            } else {
                                if (this.is_new === false) this.loadModel();
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
        appendProduct(item) {
            let assembly_item = JSON.parse(JSON.stringify(this.assembly_item));
            assembly_item.product = item;
            assembly_item.quantity = 1;
            this.assembly_items.push(assembly_item);
        },
        removeProduct(line) {
            if (this.assembly_items.length)
                this.assembly_items.splice(this.assembly_items.indexOf(line), 1);
        },
        customValidation() {
            if (_.find(this.assembly_items, function(o) { return (o.quantity < 0) })) {
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
        appendAdditionalMaterial(item) {
            let additional_material = {};
            additional_material.quantity = 1;
            additional_material.material_id = item.id;
            additional_material.material = item;
            this.additional_materials.push(additional_material);
        },
        removeAdditionalMaterial(line) {
            if (this.additional_materials.length)
                this.additional_materials.splice(this.additional_materials.indexOf(line), 1);
        },
        handleTabClick(tab, event) {
            switch (tab.name) {
                case 'crm-report-show':
                    if (!this.reportLoaded) {
                        this.$refs['crm-assembly-show-report'].loadData()
                        this.reportLoaded = true
                    }
                    break;
                case 'crm-report':
                    if (this.reportChanged) {
                        this.$refs['crm-assembly-report'].loadData()
                        this.reportChanged = false
                    }
                    break;
                default:
                    break;
            }
        },
        getProductSummeries() {
            return [
                this.$t('message.total'),
                formatNumber(_.sumBy([...this.old_items, ...this.assembly_items], function(o) { return +o.quantity })),
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