import { mapGetters, mapActions } from 'vuex';

export default {
    data() {
        return {
            form: {}
        }
    },
    computed: {
        ...mapGetters({
            model: 'priority/model',
            rules: 'priority/rules',
            columns: 'priority/columns',
            getForm: 'priority/form',
        })
    },
    methods: {
        ...mapActions({
            updatePriorityInventory: 'priority/inventory',
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
                            this.updatePriorityInventory();
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