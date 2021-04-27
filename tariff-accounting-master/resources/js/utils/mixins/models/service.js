import measurements from '@selects/crm-measurement';
import amount from '@inputs/crm-amount-input';
import { mapGetters, mapActions } from 'vuex';

export default {
    components: {
        measurements,
        amount
    },
    data() {
        return {
            form: {}
        }
    },
    computed: {
        ...mapGetters({
            model: 'services/model',
            rules: 'services/rules',
            columns: 'services/columns',
            getForm: 'services/form',
        })
    },
    methods: {
        ...mapActions({
            updateServiceInventory: 'services/inventory',
            empty: 'services/empty',
        }),
        submit(close = true) {
            this.$refs['form'].validate((valid) => {
                if (valid) {
                    this.waiting = true;
                    this.save(this.form)
                        .then(res => {
                            this.$alert(res);                            
                            this.listChanged();
                            this.updateServiceInventory();
                            this.waitingStop();
                            if( close ) {
                            	this.close();	
                            }
                        })
                        .catch(err => {
                            this.waitingStop();
                            this.$alert(err);
                        });
                }
            });
        },
        afterLeave() {
          if (_.isFunction(this.empty)) {
              this.empty();
          }
        }
    }
}