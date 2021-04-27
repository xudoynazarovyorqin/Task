import { mapGetters, mapActions } from 'vuex';

export default {
    data() {
        return {
            form: {}
        }
    },
    computed: {
        ...mapGetters({
            model: 'quarters/model',
            rules: 'quarters/rules',
            columns: 'quarters/columns',
            getForm: 'quarters/form',
        })
    },
    methods: {
        ...mapActions({
            updateQuarterInventory: 'quarters/inventory',
            empty: 'quarters/empty',
        }),
        submit(close = true) {
            this.$refs['form'].validate((valid) => {
                if (valid) {
                    this.waiting = true;
                    this.save(this.form)
                        .then(res => {
                            this.$alert(res);
                            this.listChanged();
                            this.updateQuarterInventory();
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
