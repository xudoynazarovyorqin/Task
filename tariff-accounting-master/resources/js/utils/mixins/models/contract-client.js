import clients from "@selects/crm-client";
import states from "@selects/crm-state";
import amount from '@inputs/crm-amount-input';
import { mapGetters, mapActions } from 'vuex';

export default {
    components: { clients, states, amount },
    data() {
        return {
            contract_client_suspenses: [],
        }
    },
    computed: {
        ...mapGetters({
            model: 'contractClients/model',
            getForm: 'contractClients/form',
            rules: 'contractClients/rules',
            columns: 'contractClients/columns',
            old_items: 'contractClients/contract_client_suspenses',
        })
    },
    methods: {
        ...mapActions({
            updateInventory: 'contractClients/inventory',
            empty: 'contractClients/empty',
        }),
        submit(close = true) {
            this.form["contract_client_suspenses"] = this.contract_client_suspenses;

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
                            this.waitingStop();
                            this.$alert(err);
                        });
                }
            });
        },

        addSuspense() {
            this.contract_client_suspenses.push({
                from_date: "",
                to_date: "",
                comment: "",
            });
        },
        removeSuspense(lineId) {
            if (this.contract_client_suspenses.length > 0) this.contract_client_suspenses.splice(lineId, 1);
        },
        afterLeave() {
            if (_.isFunction(this.empty)) {
                this.empty();
            }
        }
    }

}
