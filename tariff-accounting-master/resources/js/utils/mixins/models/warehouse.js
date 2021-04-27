import warehouseTypes from '@selects/crm-warehouse-type';
import { mapGetters, mapActions } from 'vuex';

export default {
    components: { warehouseTypes },
    data() {
        return {
            form: {}
        }
    },
    computed: {
        ...mapGetters({
            model: 'warehouses/model',
            rules: 'warehouses/rules',
            columns: 'warehouses/columns',
            getForm: 'warehouses/form',
        })
    },
    methods: {
        ...mapActions({
            updateWarehouseInventory: 'warehouses/inventory',
        }),
        submit(close = true) {
            this.$refs['form'].validate((valid) => {
                if (valid) {
                    this.waiting = true;
                    this.save(this.form)
                        .then(res => {
                            this.$alert(res);
                            this.close();
                            this.listChanged();
                            this.updateWarehouseInventory();
                            this.waitingStop();
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