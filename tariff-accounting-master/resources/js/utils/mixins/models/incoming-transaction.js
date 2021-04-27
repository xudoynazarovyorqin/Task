import amount from '@inputs/crm-amount-input';
import clients from '@selects/crm-client';
import contracts from '@selects/crm-contract-client';
import { mapGetters, mapActions } from 'vuex';

export default {
    components: { amount, contracts, clients },
    data() {
        return {
            client_id: null,
            contract_client_id: null,
            console_number: null,
            loadApplication: false,
        }
    },
    computed: {
        ...mapGetters({
            rules: 'transactions/rules',
            columns: 'transactions/columns',
            getForm: 'transactions/form',
        }),
    },
    watch: {

    },
    methods: {
        ...mapActions({
            updateContractClientInventory: 'contractClients/inventory'
        }),
        submit(close = true) {
            if (!this.waiting) {
                this.$refs['form'].validate((valid) => {
                    if (valid) {
                        this.waiting = true;
                        this.save(this.form)
                            .then(res => {
                                this.$alert(res);
                                this.updateContractClientInventory();
                                this.listChanged();
                                this.waitingStop();
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
    }
}
