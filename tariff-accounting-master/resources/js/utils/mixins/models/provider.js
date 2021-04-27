import types from "@selects/crm-agent-type";
import { mapGetters, mapActions } from 'vuex';

export default {
    components: { types },
    data() {
        return {
            provider_contact_persons: [],
            provider_checking_accounts: [],
        }
    },
    computed: {
        ...mapGetters({
            rules: 'providers/rules',
            model: 'providers/model',
            columns: 'providers/columns',
            types: 'providers/types',
            getForm: 'providers/form'
        })
    },
    methods: {
        ...mapActions({
            updateInventory: 'providers/inventory'
        }),
        submit(close = true) {
            this.form["provider_contact_persons"] = this.provider_contact_persons;
            this.form["provider_checking_accounts"] = this.provider_checking_accounts;
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
            this.provider_contact_persons.push({
                full_name: "",
                position: "",
                phone: "",
                email: "",
                comment: ""
            });
        },
        removeContactPerson(lineId) {
            if (this.provider_contact_persons.length > 0) this.provider_contact_persons.splice(lineId, 1);
        },
        addCheckingAccount() {
            this.provider_checking_accounts.push({
                bank: "",
                address: "",
                correspondent_account: "",
                checking_account: ""
            });
        },
        removeCheckingAccount(lineId) {
            if (this.provider_checking_accounts.length > 0) this.provider_checking_accounts.splice(lineId, 1);
        },
    }
}