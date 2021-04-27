import providers from "@selects/crm-provider";
import contracts from "@selects/crm-contract-provider";
import states from "@selects/crm-state";
import amount from '@inputs/crm-amount-input';
import { mapGetters, mapActions } from 'vuex';

export default {
    components: { providers, states, contracts, amount },
    computed: {
        ...mapGetters({
            rules: 'contractProviders/rules',
            model: 'contractProviders/model',
            columns: 'contractProviders/columns',
            getForm: 'contractProviders/form'
        })
    },
    methods: {
        ...mapActions({
            updateInventory: 'contractProviders/inventory'
        }),
        submit(close = true) {
            this.$refs['form'].validate((valid) => {
                if (valid) {
                    this.waiting = true;
                    this.save(this.form)
                        .then(res => {
                            this.$alert(res);
                            this.updateInventory();
                            this.listChanged();
                            this.waitingStop();
                            if (close) this.close()
                        })
                        .catch(err => {
                            this.$alert(err);
                        });
                }
            });
        }
    }

}