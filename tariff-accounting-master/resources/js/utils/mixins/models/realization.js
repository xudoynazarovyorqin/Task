import { mapGetters, mapActions } from 'vuex';
import users from '@selects/crm-user';
import materials from '@selects/crm-material';
import realizationTypes from '@selects/crm-realizationable-type';

export default {
    components: {
        users,
        realizationTypes,
        materials
    },
    data() {
        return {
            form: {},
            items: [],
            includeDrawer: false,
            loadingMaterial: false
        }
    },
    computed: {
        ...mapGetters({
            model: 'realizations/model',
            rules: 'realizations/rules',
            columns: 'realizations/columns',
            getForm: 'realizations/form',
            selectedRow: 'realizations/selectedRow',
        })
    },
    methods: {
        ...mapActions({
            showMaterial: 'materials/show',
        }),
        submit(close = true) {

            /**
             * Set items inventory
             */

            this.form['items'] = _.map(this.items, function(item) {
                return {
                    material_id: item.material ? item.material.id : null,
                    quantity: item.quantity,
                    issued_from_booked: item.issued_from_booked
                }
            })


            this.$refs['form'].validate((valid) => {
                if (valid) {
                    this.waiting = true;
                    this.save(this.form)
                        .then(res => {
                            this.$alert(res);
                            this.close();
                            this.listChanged();
                            this.waitingStop();
                        })
                        .catch(err => {
                            this.waitingStop();
                            this.$alert(err);
                        });
                }
            });
        },
        appendMaterial(material) {
            this.loadingMaterial = true;
            this.showMaterial({ id: material.id, params: { warehouse_info: true } })
                .then(res => {
                    this.loadingMaterial = false;
                    if (res.data) {
                        this.pushMaterial(res.data.material, res.data.available, res.data.booked)
                    }
                })
                .catch(err => {
                    this.loadingMaterial = false;
                })
        },
        pushMaterial(material, available = 0, booked = 0, qty = 0, issued_from_booked = 0) {
            if (!_.some(this.items, ['material.id', material.id])) {
                let item = {};
                item.material = material;
                item.available = available;
                item.booked = booked;
                item.quantity = qty;
                item.issued_from_booked = issued_from_booked;
                this.items.push(item);
            }
        },
        deleteRow(index, rows) {
            this.$confirm(this.$t('message.delete_confirm'), this.$t('message.confirm'), {
                confirmButtonText: this.$t('message.yes'),
                cancelButtonText: this.$t('message.cancel'),
                type: 'warning'
            }).then(() => {
                rows.splice(index, 1);
            }).catch(() => {});
        },

        closeDrawer(drawer) {
            if (this.$refs[drawer] && _.isFunction(this.$refs[drawer].closeDrawer)) {
                this.$refs[drawer].closeDrawer();
            }
        },
        drawerClosed(ref) {
            if (this.$refs[ref] && _.isFunction(this.$refs[ref].closed)) {
                this.$refs[ref].closed()
            }

            if (this.selectedRow && this.form.realizationable_id != this.selectedRow.id) {
                this.loadReservationsForDocuement();
                this.form.realizationable_type = this.selectedRow ? this.selectedRow.realizationable_type : null;
                this.form.realizationable_id = this.selectedRow ? this.selectedRow.realizationable_id : null;
            }
        },
        drawerOpened(ref) {
            if (this.$refs[ref] && _.isFunction(this.$refs[ref].opened)) {
                this.$refs[ref].opened()
            }
        },
        loadReservationsForDocuement() {
            this.items = [];
            this.loadingMaterial = true;
            this.loadReservations(this.selectedRow)
                .then(res => {
                    this.loadingMaterial = false;
                    if (res.data && res.data.reservations) {
                        res.data.reservations.forEach(element => {
                            this.pushMaterial(element.source, element.available, element.booked, 0, element.quantity - element.issued)
                        });
                    }
                }).catch(err => {
                    this.loadingMaterial = false;
                    this.$alert(err)
                })

        },
    }
}