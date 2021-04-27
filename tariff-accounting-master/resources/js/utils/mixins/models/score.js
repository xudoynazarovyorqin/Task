import { mapGetters, mapActions } from 'vuex';
import currencies from '@inventory/crm-currency-select';

export default {
    components: {
        currencies
    },
    data() {
        return {
            form: {}
        }
    },
    computed: {
        ...mapGetters({
            model: 'scores/model',
            rules: 'scores/rules',
            columns: 'scores/columns',
            getForm: 'scores/form',
        })
    },
    methods: {
        ...mapActions({
            updateStateInventory: 'scores/inventory',
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
                            this.updateStateInventory();
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