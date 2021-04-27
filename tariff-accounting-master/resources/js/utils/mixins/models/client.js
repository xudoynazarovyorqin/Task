import types from "@selects/crm-agent-type";
import districts from '@selects/crm-district';
import quarters from '@selects/crm-quarter';
import { mapGetters, mapActions } from 'vuex';

export default {
    components: { types, districts, quarters },
    data() {
        return {
            client_contact_persons: [],
            client_checking_accounts: [],
        }
    },
    computed: {
        ...mapGetters({
            rules: 'clients/rules',
            model: 'clients/model',
            columns: 'clients/columns',
            types: 'clients/types',
            getForm: 'clients/form'
        })
    },
    methods: {
        ...mapActions({
            updateInventory: 'clients/inventory'
        }),
        submit(close = true) {
            this.form["client_contact_persons"] = this.client_contact_persons;
            this.form["client_checking_accounts"] = this.client_checking_accounts;
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
        addContactPerson() {
            this.client_contact_persons.push({
                full_name: "",
                position: "",
                phone: "",
                email: "",
                comment: ""
            });
        },
        removeContactPerson(lineId) {
            if (this.client_contact_persons.length > 0) this.client_contact_persons.splice(lineId, 1);
        },
        addCheckingAccount() {
            this.client_checking_accounts.push({
                bank: "",
                address: "",
                correspondent_account: "",
                checking_account: ""
            });
        },
        removeCheckingAccount(lineId) {
            if (this.client_checking_accounts.length > 0) this.client_checking_accounts.splice(lineId, 1);
        },
    }
}
