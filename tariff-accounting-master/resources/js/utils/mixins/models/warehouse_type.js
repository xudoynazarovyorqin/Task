import { mapGetters, mapActions } from 'vuex';

export default {
    data() {
        return {
            form: {}
        }
    },
    computed: {
        ...mapGetters({
            model: 'warehouseTypes/model',
            rules: 'warehouseTypes/rules',
            columns: 'warehouseTypes/columns',
            getForm: 'warehouseTypes/form',
        })
    },
    methods: {
        ...mapActions({
            updateWarehouseTypeInventory: 'warehouseTypes/inventory',
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
                            this.updateWarehouseTypeInventory();
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