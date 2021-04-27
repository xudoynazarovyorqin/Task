import roles from '@selects/crm-role';
import { mapGetters, mapActions } from "vuex";

export default {
    components: { roles },
    data() {
        return {
            form: {},
            show_employee_groups: false,
        }
    },
    computed: {
        ...mapGetters({
            columns: 'users/columns',
            model: 'users/model',
            rules: 'users/rules',
            rules: 'users/rules',
            roles: 'roles/list',
            statues: 'users/statues',
            getForm: 'users/form',
            employee_groups: 'employeeGroups/list',
        }),
    },
    watch: {
        'form.is_employee': {
            handler: function(new_value, old_value) {
                if (new_value) {
                    this.show_employee_groups = true;
                } else {
                    this.show_employee_groups = false;
                }
            },
            deep: true
        }
    },
    created() {
        if (!_.size(this.employee_groups)) this.loadEmployeeGroups();
    },
    methods: {
        ...mapActions({
            loadEmployeeGroups: 'employeeGroups/index',
            updateUserInventory: 'users/inventory'
        }),
        submit(close = true) {
            this.$refs['form'].validate((valid) => {
                if (valid) {
                    this.save(this.form)
                        .then(res => {
                            this.$alert(res);
                            this.close();
                            this.listChanged();
                            this.updateUserInventory();
                            this.waitingStop();
                        })
                        .catch(err => {
                            this.waitingStop();
                            this.$alert(err);
                        });
                }
            });
        }
    }
}