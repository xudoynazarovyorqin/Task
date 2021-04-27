import { mapGetters, mapActions } from 'vuex';
export default {
    props: ['role', 'open'],
    data() {
        return {
            defaultProps: {
                children: 'children',
                label: 'name'
            }
        }
    },
    computed: {
        ...mapGetters({
            dataPermissions: "permissions/parent_permissions",
            rules: 'roles/rules',
            model: 'roles/model',
            columns: 'roles/columns',
            getForm: 'roles/form'
        })
    },
    created() {},
    methods: {
        ...mapActions({
            empty: 'roles/empty',
        }),
        submit() {
            let checkedPermissions = this.$refs.tree.getCheckedNodes();
            let perms = [];
            for (let key in checkedPermissions) {
                if (checkedPermissions.hasOwnProperty(key)) {
                    let checkedPermission = checkedPermissions[key];
                    perms.push(checkedPermission.id)
                }
            }
            this.form['permissions'] = perms;
            this.$refs['form'].validate((valid) => {
                if (valid) {
                    this.waiting = true;
                    this.save(this.form)
                        .then(res => {
                            this.$alert(res);
                            this.listChanged();
                            this.waitingStop();
                            this.close();
                        })
                        .catch(err => {
                            this.waitingStop();
                            this.$alert(err);
                        });
                }
            });
        },
        afterLeave() {
            this.empty()
        }
    }
}