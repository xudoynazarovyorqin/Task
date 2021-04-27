import { mapGetters, mapActions } from 'vuex';
import levels from '@selects/crm-level';

export default {
    components: { levels },
    data() {
        return {
            form: {}
        }
    },
    computed: {
        ...mapGetters({
            model: 'levels/model',
            rules: 'levels/rules',
            columns: 'levels/columns',
            getForm: 'levels/form',
        })
    },
    methods: {
        ...mapActions({
            updateLevelInventory: 'levels/inventory',
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
                            this.updateLevelInventory();
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