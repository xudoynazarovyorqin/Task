import { mapGetters, mapActions } from 'vuex';

export default {
    data() {
        return {
            form: {}
        }
    },
    computed: {
        ...mapGetters({
            model: 'districts/model',
            rules: 'districts/rules',
            columns: 'districts/columns',
            getForm: 'districts/form',
        })
    },
    methods: {
        ...mapActions({
            updateDistrictInventory: 'districts/inventory',
            empty: 'districts/empty',
        }),
        submit(close = true) {
            this.$refs['form'].validate((valid) => {
                if (valid) {
                    this.waiting = true;
                    this.save(this.form)
                        .then(res => {
                            this.$alert(res);
                            this.listChanged();
                            this.updateDistrictInventory();
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
