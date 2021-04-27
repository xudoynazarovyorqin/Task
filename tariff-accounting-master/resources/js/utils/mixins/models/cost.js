import currencies from '@inventory/crm-currency-select';
import price from '@inputs/crm-cost-price-input';

import { mapGetters, mapActions } from 'vuex';

export default {
    components: { currencies, price },
    computed: {
        ...mapGetters({
            model: 'costs/model',
            rules: 'costs/rules',
            columns: 'costs/columns',
            getForm: 'costs/form',
            currencies: 'currencies/inventory'
        })
    },
    methods: {
        ...mapActions({
            updateInventory: 'costs/inventory',
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
                            this.updateInventory();
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