import { mapGetters, mapActions } from 'vuex';

export default {
    data() {
        return {
            form: {}
        }
    },
    computed: {
        ...mapGetters({
            model: 'category/model',
            rules: 'category/rules',
            columns: 'category/columns',
            getForm: 'category/form',
            categories: 'category/inventory',
        })
    },
    mounted() {
        if (this.categories && this.categories.length == 0) this.updateCategoryInventory()
    },
    methods: {
        ...mapActions({
            updateCategoryInventory: 'category/inventory',
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
                            this.updateCategoryInventory();
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